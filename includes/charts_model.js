var series_activity_by_user = new Array();
var series_activity_user_by_day = new Array();
var series_activity_user_by_days = new Array();

var my_series;
var my_hours_count;
var categories = new Array();
var names = new Array();
var current_user_number;
var days = new Array();
var cat_counter = 0;

function generate_array_for_graphs(data, php_names, length){
    my_hours_count = new Array(length);
    categories[cat_counter] = new Array(length);
    my_series = new Array();
    data = JSON.parse(data);
    names = JSON.parse(php_names);
    current_user_number = 0; //Number of current user
    new_user_index = 0; //Array number where starts new user

    for(var i = 0; i < data.length - 1; i++) {
        current_id = data[i][0];
        next_id = data[i + 1][0];

        if (current_id == next_id){
    	    categories[cat_counter][i - new_user_index] = data[i][1];
	    my_hours_count[i - new_user_index] = parseInt(data[i][2], 10);

	    categories[cat_counter][i + 1 - new_user_index] = data[i + 1][1];
	    my_hours_count[i + 1 - new_user_index] = parseInt(data[i + 1][2], 10);
        } else {
	    save_cleared_series(length);    	    
            current_user_number++;

	    categories[cat_counter] = new Array(length);
	    my_hours_count = new Array(length);
	    categories[cat_counter][i + 1 - new_user_index] = data[i + 1][1];
	    my_hours_count[i + 1 - new_user_index] = parseInt(data[i + 1][2], 10);
	    new_user_index = i + 1;
        }
    }
    save_cleared_series(length);
    cat_counter++;

    return my_series;
}

function save_cleared_series(length){
    //If not hours clearing run without checking data
    if (length == 24){
	my_hours_count = remove_empty_hourse(categories[cat_counter],my_hours_count,length);
    } else {
	my_hours_count = remove_empty_dates(categories[cat_counter],my_hours_count,length);
    }
    //my_hours_count = normalise_hours(remove_empty_hourse(categories,my_hours_count),days,current_user_number);
    
    my_series[current_user_number] = {
        name: names[current_user_number],
        data: my_hours_count
    };
}

function remove_empty_hourse(hours, data, length){
    rhours = new Array(length);

    for (i = 0; i < rhours.length; i++){
	rhours[i] = i;
    }

    return data_corrector(rhours, hours, data);
}

function remove_empty_dates(raw_dates, data, length){
    return data_corrector(get_curren_interval(), raw_dates, data);
}

function data_corrector(correct_categories, raw_categories, data){
    var corrected_data = new Array(correct_categories.length);

    for (i = 0; i < correct_categories.length; i++){
	for (j = 0; j < raw_categories.length; j++){
	    if ("" + raw_categories[j] == correct_categories[i] + ""){ //TODO bug 0 item not compairing
		corrected_data[i] = data[j];
		continue;
	    } else if (typeof corrected_data[i] === 'undefined'){
		corrected_data[i] = 0;
	    }
	}
    }
    categories[cat_counter] = correct_categories;

    return corrected_data;    
}

function post(path, params, method) {
    console.log(params);
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

function to_date(str){
    //Detect date from 13.11.15 like format
    var res = str.split(".");
    return new Date("20" + res[2],res[1] - 1,res[0]);
}

function get_curren_interval(){
    return get_all_days(to_date(get_range()[0]), to_date(get_range()[1]));
}

function get_all_days(s, e){
    var a = [];
    
    while(s <= e){
        a.push(s);
        s = new Date(s.setDate(
            s.getDate() + 1
        ))
    }

    //Convert date format to like 2015-11-04
    for (i = 0; i < a.length; i++){
	a[i] = a[i].toISOString().slice(0,10);
    }

    return a;
};

function normalise_hours(data,days,id){
    for(var i = 0; i < data.length - 1; i++) {
	data[i] = data[i] / days[id][1];
    }
    return data;
}


function get_range(){
    var fdate = new Array(2);
    fdate[0] = document.getElementsByName('start')[0].value;
    fdate[1] = document.getElementsByName('end')[0].value;
return fdate;
}