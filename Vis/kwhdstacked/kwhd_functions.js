
/*
   All Emoncms code is released under the GNU General Public License v3.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/
        function get_range(data,start,end)
        {
          var gdata = [];
          for (var z in data)                     //for all variables
          {
            if (data[z][0] >= start && data[z][0] < end) gdata[z] = data[z];
          } 
          return gdata;
        }

        function get_days_month(data,month,year)
        {
          return get_range(data,Date.UTC(year,month,1),Date.UTC(year,month+1,1));
        }

        function get_months(data)
        {
          var gdata = [];

          var sum=0, s=0, i=0;
          var lmonth=0,month=0,year;
          var tmp = []
          var d = new Date();

          for (var z in data)
          {
            lmonth = month;

   
            d.setTime(data[z][0]);
            month = d.getMonth();
            year = d.getFullYear();
            sum += parseFloat(data[z][1]);
            s++;
            
            if (month!=lmonth && z!=0)
            { 
              var tmp = [];
              tmp[0] = Date.UTC(year,month-1,1);
              tmp[1] = sum/daysInMonth(month, year);

              gdata[i] = tmp; i++;
              sum = 0; s = 0;
            }
          } 
          var tmp = [];
          tmp[0] = Date.UTC(year,month,1);
          tmp[1] = sum/daysInMonth(month, year);
          gdata[i] = tmp;

          return gdata;
        }

        function daysInMonth(iMonth, iYear)
        {
	  return 32 - new Date(iYear, iMonth, 32).getDate();
        }
