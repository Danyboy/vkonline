function generate_array_for_graphs(data, php_names, length){
    my_hours_count = new Array(length);
    categories[cat_counter] = new Array(length);
    my_series = new Array();
    data = JSON.parse(data);
    names = JSON.parse(php_names);
    my_series_count = 0; //Number of current user
    prevCounter = 0; //Array number where starts new user

    for(var i = 0; i < data.length - 1; i++) {
        current_id = data[i][0];
        next_id = data[i + 1][0];

        if (current_id == next_id){
    	    categories[cat_counter][i - prevCounter] = data[i][1];
	    my_hours_count[i - prevCounter] = parseInt(data[i][2], 10);

	    categories[cat_counter][i + 1 - prevCounter] = data[i + 1][1];
	    my_hours_count[i + 1 - prevCounter] = parseInt(data[i + 1][2], 10);
        } else {
	    save_cleared_series(length);    	    
            my_series_count++;

	    categories[cat_counter] = new Array(length);
	    my_hours_count = new Array(length);
	    categories[cat_counter][i + 1 - prevCounter] = data[i + 1][1];
	    my_hours_count[i + 1 - prevCounter] = parseInt(data[i + 1][2], 10);
	    prevCounter = i + 1;
        }
    }
    save_cleared_series(length);
    cat_counter++;

    return my_series;
}

function save_cleared_series(length){
    //If not hours run without checking data
    if (length == 24){
	my_hours_count = remove_empty_hourse(categories[cat_counter],my_hours_count,length);
    }
    //my_hours_count = normalise_hours(remove_empty_hourse(categories,my_hours_count),days,my_series_count);
    
    my_series[my_series_count] = {
        name: names[my_series_count],
        data: my_hours_count
    };
}

function normalise_hours(data,days,id){
    for(var i = 0; i < data.length - 1; i++) {
	data[i] = data[i] / days[id][1];
    }
    return data;
}

function remove_empty_hourse(hours,data, length){
    rhours = new Array(length);
    rdata = new Array(length);
    for (i = 0; i < rhours.length; i++){
	rhours[i] = i;
	for (j = 0; j < hours.length; j++){
	    if (hours[j] == i){
		rdata[i] = data[j];
		continue;
	    } else if (typeof rdata[i] === 'undefined'){
		rhours[i] = i;
		rdata[i] = 0;
	    }
	}
    }
    categories[cat_counter] = rhours;
    
    return rdata;    
}

function graph_by_ids(){
    series_activity_by_user = generate_array_for_graphs(data, php_names, 24);
    series_activity_user_by_day = generate_array_for_graphs(data_by_day, php_names, 24);
    series_activity_user_by_days = generate_array_for_graphs(data_by_days, php_names, 315);
}


function get_range(){
    var fdate = new Array(2);
    fdate[0] = document.getElementsByName('start')[0].value;
    fdate[1] = document.getElementsByName('end')[0].value;
return fdate;
}

function my_init(){
series_activity_by_user = new Array();
series_activity_user_by_day = new Array();
series_activity_user_by_days = new Array();

graph_by_ids();
}

var series_activity_by_user = new Array();
var series_activity_user_by_day = new Array();
var series_activity_user_by_days = new Array();

var my_series = new Array();
var my_hours_count = new Array();
var categories = new Array();
var names = new Array();
var my_series_count = 0;
var days = new Array();
var cat_counter = 0;
