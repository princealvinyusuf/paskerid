<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardPerformanceSeeder extends Seeder
{
    public function run()
    {
        DB::table('dashboard_performance')->insert([
            'name' => 'Dashboard Performance',
            'iframe_code' => "<div class='tableauPlaceholder' id='viz1751516254923' style='position: relative'><noscript><a href='#'><img alt='Dashboard Performance' src='https://public.tableau.com/static/images/Da/DataCollectingDashboardV_2/Dashboard/1_rss.png' style='border: none' /></a></noscript><object class='tableauViz'  style='display:none;'><param name='host_url' value='https%3A%2F%2Fpublic.tableau.com%2F' /> <param name='embed_code_version' value='3' /> <param name='site_root' value='' /><param name='name' value='DataCollectingDashboardV_2/Dashboard' /><param name='tabs' value='no' /><param name='toolbar' value='yes' /><param name='static_image' value='https://public.tableau.com/static/images/Da/DataCollectingDashboardV_2/Dashboard/1.png' /> <param name='animate_transition' value='yes' /><param name='display_static_image' value='yes' /><param name='display_spinner' value='yes' /><param name='display_overlay' value='yes' /><param name='display_count' value='yes' /><param name='language' value='en-US' /></object></div>                <script type='text/javascript'>                    var divElement = document.getElementById('viz1751516254923');                    var vizElement = divElement.getElementsByTagName('object')[0];                    if ( divElement.offsetWidth > 800 ) { vizElement.style.width='1400px';vizElement.style.height='1327px';} else if ( divElement.offsetWidth > 500 ) { vizElement.style.width='1400px';vizElement.style.height='1327px';} else { vizElement.style.width='100%';vizElement.style.height='4177px';}                     var scriptElement = document.createElement('script');                    scriptElement.src = 'https://public.tableau.com/javascripts/api/viz_v1.js';                    vizElement.parentNode.insertBefore(scriptElement, vizElement);                </script>",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
} 