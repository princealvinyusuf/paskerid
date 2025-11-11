<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirtualKarirService;
use App\Models\VirtualKarirJobFair;
use App\Models\BookedDate;
use App\Models\Kemitraan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VirtualKarirController extends Controller
{
    public function index()
    {
        $services = VirtualKarirService::all();
        $jobFairs = VirtualKarirJobFair::orderBy('date', 'desc')->get();
        
        // Fetch agendas from Kemitraan Booked
        $agendas = $this->getAgendasFromBookedDates();
        
        return view('virtual_karir', compact('services', 'jobFairs', 'agendas'));
    }

    private function getAgendasFromBookedDates()
    {
        $hasRange = Schema::hasColumn('booked_date', 'booked_date_start');
        $hasTimeRange = Schema::hasColumn('booked_date', 'booked_time_start');
        
        $today = Carbon::today()->format('Y-m-d');
        
        // Build query with relationships
        $query = BookedDate::with([
            'kemitraan.typeOfPartnership',
            'kemitraan.rooms',
            'kemitraan.facilities',
            'typeOfPartnership'
        ])
        ->whereHas('kemitraan', function($q) {
            // Only show approved kemitraan bookings in the public agenda
            if (Schema::hasColumn('kemitraan', 'status')) {
                $q->where('status', 'approved');
            }
        });

        // Filter to show only upcoming events (today and future)
        if ($hasRange) {
            // For date ranges, show events where start date is today or in the future
            $query->where('booked_date_start', '>=', $today);
            $query->orderBy('booked_date_start', 'asc');
        } else {
            // For single dates, show events where date is today or in the future
            $query->where('booked_date', '>=', $today);
            $query->orderBy('booked_date', 'asc');
        }

        $bookedDates = $query->get();
        
        // Transform to agenda-like structure
        $agendas = collect();
        
        foreach ($bookedDates as $bookedDate) {
            $kemitraan = $bookedDate->kemitraan;
            if (!$kemitraan) continue;
            
            // Get date
            $date = $hasRange 
                ? ($bookedDate->booked_date_start ?? $bookedDate->booked_date)
                : $bookedDate->booked_date;
            
            if (!$date) continue;
            
            // Ensure date is in Y-m-d format
            $dateStr = $date instanceof Carbon 
                ? $date->format('Y-m-d') 
                : (is_string($date) ? $date : Carbon::parse($date)->format('Y-m-d'));
            
            // Get partnership type name
            $partnershipType = $bookedDate->typeOfPartnership 
                ?? $kemitraan->typeOfPartnership;
            $partnershipTypeName = $partnershipType ? $partnershipType->name : 'Kegiatan Kemitraan';
            
            // Get room information
            $rooms = $kemitraan->rooms->pluck('room_name')->toArray();
            $roomNames = !empty($rooms) 
                ? implode(', ', $rooms) 
                : ($kemitraan->other_pasker_room ?? '-');
            
            // Get facility information
            $facilities = $kemitraan->facilities->pluck('facility_name')->toArray();
            $facilityNames = !empty($facilities) 
                ? implode(', ', $facilities) 
                : ($kemitraan->other_pasker_facility ?? '-');
            
            // Build location string
            $location = trim($roomNames . ($facilityNames !== '-' ? ' - ' . $facilityNames : ''), ' -');
            
            // Get time information
            $timeInfo = '';
            if ($hasTimeRange) {
                // booked_time_start and booked_time_finish are TIME fields (HH:MM:SS format)
                $timeStart = $bookedDate->booked_time_start 
                    ? substr($bookedDate->booked_time_start, 0, 5) // Get HH:MM
                    : ($kemitraan->scheduletimestart ?? '');
                $timeFinish = $bookedDate->booked_time_finish 
                    ? substr($bookedDate->booked_time_finish, 0, 5) // Get HH:MM
                    : ($kemitraan->scheduletimefinish ?? '');
                if ($timeStart && $timeFinish) {
                    $timeInfo = $timeStart . ' - ' . $timeFinish;
                } elseif ($timeStart) {
                    $timeInfo = $timeStart;
                }
            } else {
                // booked_time is a TIME field (HH:MM:SS format)
                $timeStart = $bookedDate->booked_time 
                    ? substr($bookedDate->booked_time, 0, 5) // Get HH:MM
                    : ($kemitraan->scheduletimestart ?? '');
                $timeFinish = $kemitraan->scheduletimefinish ?? '';
                if ($timeStart && $timeFinish) {
                    $timeInfo = $timeStart . ' - ' . $timeFinish;
                } elseif ($timeStart) {
                    $timeInfo = $timeStart;
                }
            }
            
            // Create agenda object
            $agenda = (object) [
                'id' => $bookedDate->id,
                'title' => $partnershipTypeName . ($kemitraan->institution_name ? ' - ' . $kemitraan->institution_name : ''),
                'description' => ($kemitraan->institution_name ?? '') . 
                    ($timeInfo ? ' (' . $timeInfo . ')' : '') . 
                    ($location && $location !== '-' ? ' - Lokasi: ' . $location : ''),
                'date' => $dateStr,
                'location' => $location ?: '-',
                'organizer' => $kemitraan->institution_name ?? '-',
                'image_url' => null, // No image URL in booked dates
                'registration_url' => null, // No registration URL in booked dates
            ];
            
            $agendas->push($agenda);
        }
        
        // Sort by date
        return $agendas->sortBy('date')->values();
    }
} 