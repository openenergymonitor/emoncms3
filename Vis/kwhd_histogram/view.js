  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

        function set_daily_view()
        {
          $('#inst-buttons').hide();
          bargraph(days,3600*22);
          $("#out").html(""); view = 2;
          $("#return").val("View: monthly view");
          $("#out2").html("Daily view");
          $('#axislabely').html("Energy<br/ >(kWh)");
          $('#axislabelx').html("Date");
          $("#bot_out").html(bot_kwhd_text);
          $("#return_ctr").show();
          $("#enableHistogram").show();
        }

        function set_monthly_view()
        {
          bargraph(months.data,3600*24*20); 
          $("#out").html(""); view = 1;
          $("#return").val("View: annual view");               
          $("#out2").html("Monthly view");
          $('#axislabely').html("Energy<br/ >(kWh)");
          $('#axislabelx').html("Date");
          $("#return_ctr").show();
          $("#enableHistogram").show();
        }

        function set_annual_view()
        {
          bargraph(years.data,3600*24*330);     
          $("#out").html(""); view = 0; 
          $("#out2").html("Annual view");
          $('#axislabely').html("Energy<br/ >(kWh)");
          $('#axislabelx').html("Date");
          $("#return_ctr").hide();
          $("#enableHistogram").show();
        }

        function set_last30days_view()
        {
          bargraph(days,3600*22);
          $("#out").html(""); view = 2;
          $("#return").val("View: monthly view");
          $("#out2").html("Last 30 days");
          $('#axislabely').html("Energy<br/ >(kWh)");
          $('#axislabelx').html("Date");
          $("#bot_out").html(bot_kwhd_text);
          $("#return_ctr").show();
          $("#enableHistogram").show();
        }

        //--------------------------------------------------------------------------
        // Inst graphing
        //--------------------------------------------------------------------------
        function set_inst_view(day)
        {
        	$('#loading').show();
              $("#out").html("Loading...  please wait about 5s");

              start = day;
              end = day + 3600000 * 24;
              vis_feed_data(apikey,power,start,end,1);

              view = 3;
              $("#out2").html("Power view");
              $("#return").val("View: daily view");
              $('#axislabely').html("Power<br />(Watts)");
              $('#axislabelx').html("Date / Time");
              $('#inst-buttons').show();
              $("#return_ctr").show();
              $("#enableHistogram").show();
        }

        //--------------------------------------------------------------------------
        // Histogram graphing
        //--------------------------------------------------------------------------
        function set_histogram_view(start, end)
        {
        	$('#loading').show();
              $("#out").html("Loading...");

              //start = day;
              //end = day + 3600000 * 24;
              vis_feed_data(apikey,whw,start,end,1);

              last_view = view;
              view = 5;
              var ret;
              if (last_view==0) ret = "annual";   
              if (last_view==1) ret = "monthly";
              if (last_view==2) ret = "daily";
              if (last_view==3) ret = "daily";
              $("#out2").html("Histogram "+ret);
              //$("#return").val("View: back");
              $("#return").val("View: "+ret);
              $('#axislabely').html("Energy<br/>(Watt<br/>hours)");
              $('#axislabelx').html("Watts");
              $('#inst-buttons').hide();
              $("#return_ctr").show();
              $("#enableHistogram").hide();
              $('#enableHistogram').removeAttr('checked');
        }
