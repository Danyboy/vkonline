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
	    my_hours_count[i - new_user_index] = parseFloat(data[i][2], 10);

	    categories[cat_counter][i + 1 - new_user_index] = data[i + 1][1];
	    my_hours_count[i + 1 - new_user_index] = parseFloat(data[i + 1][2], 10);
        } else {
	    save_cleared_series(length);    	    
            current_user_number++;

	    new_user_index = i;
	    categories[cat_counter] = new Array(length);
	    my_hours_count = new Array(length);
	    categories[cat_counter][i + 1 - new_user_index] = data[i + 1][1];
	    my_hours_count[i + 1 - new_user_index] = parseFloat(data[i + 1][2], 10);
	    new_user_index = i + 1;
        }
    //console.log(my_hours_count,current_id,next_id);
    }
    save_cleared_series(length);
    cat_counter++;

    return my_series;
}

function save_cleared_series(length){
    //If not hours clearing run without checking data
    if (length == 24){
	my_hours_count = remove_empty_hourse(categories[cat_counter],my_hours_count,length);
    } else if (length == 19){
	my_hours_count = remove_empty_dates_without_year(categories[cat_counter],my_hours_count,length);
    } else {
	my_hours_count = remove_empty_dates(categories[cat_counter],my_hours_count,length);
    }
    
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

function remove_empty_dates_without_year(raw_dates, data, length){
    return data_corrector(get_curren_interval_without_year(), raw_dates, data);
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

function to_date(str){
    //Detect date from 13.11.15 like format
    var res = str.split(".");
    return new Date("20" + res[2],res[1] - 1,res[0]);
}

function get_curren_interval(){
    return convert_date(get_all_days(to_date(get_range()[0]), to_date(get_range()[1])));
}

function get_curren_interval_without_year(){
    return convert_date_without_year(get_all_days(to_date(get_range()[0]), to_date(get_range()[1])));
}

function get_range(){
    var fdate = new Array(2);
    fdate[0] = document.getElementsByName('start')[0].value;
    fdate[1] = document.getElementsByName('end')[0].value;
return fdate;
}

function get_all_days(s, e){
    var a = [];
    
    while(s <= e){
        a.push(s);
        s = new Date(s.setDate(
            s.getDate() + 1
        ))
    }

    return a;
}

function convert_date(a){
    //Convert date format to like 2015-11-04
    for (i = 0; i < a.length; i++){
	a[i] = a[i].toISOString().slice(0,10);
    }

    return a;
}

function convert_date_without_year(a){
    //Convert date format to like 2015-11-04
    for (i = 0; i < a.length; i++){
	a[i] = a[i].toISOString().slice(5,10);
    }

    return a;
}

function normalise_hours(data,days,id){
    for(var i = 0; i < data.length - 1; i++) {
	data[i] = data[i] / days[id][1];
    }
    return data;
}

function change_info_for_logged(id){
    document.getElementById("main_info").innerHTML = document.getElementById("main_info").innerHTML.replace(/<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>/g, id);
}

function add_follower(id) {
    var req = 'user=' + encodeURIComponent(id);
    xhttp = new XMLHttpRequest();
    xhttp.open("POST", "includes/add_follower.php", true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send(req);
}

function send_request(path) {
    //xhttp.open("GET", path, true);
    var myjson;
    $.getJSON(path, function(json){
    myjson = json;
    });
    return myjson;
}

function get_date_and_users(){
    var result;
    //TODO maybe always added current user?
    //result += "u?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>";
    my_users=get_checked_users(document.querySelectorAll('input[name=mycheckbox]:checked'));
    my_date=$('.datepicker').val();
    if (my_users != '') {
	result = '&users=['+my_users+']'+'&d='+my_date;
    } else {
	result = '&d='+my_date;
    }
    return result;
}

function get_checked_users(input){
    var users = new Array(input.length);
    for (i = 0; i < input.length; i++){
        users[i] = input[i].value;
    }

    return users;
}

function add_logged_user(id) {
    var table = document.getElementById('users_statistics');
    var reg = new RegExp("id"+id, "g");
    var tbody = table.children[0];

    for (var r = 0; r < table.rows.length; r++) {
        var current_row = table.rows[r];
        if (reg.test(current_row.innerHTML)){
    	    tbody.insertBefore(current_row, table.rows[0]);
        }
    }
}

function frame_killer(){
    if(self == top) {
	document.documentElement.style.display = 'block'; 
    } else {
	top.location = self.location; 
    }
}

function set_user_url(id){
	//frame_killer();
	if ( ! (location.search.indexOf("?u="+id) > -1)){
	    document.location.assign("/?u=" + id);
	}
}

function stateChange(id) {
    setTimeout(function () {
	    console.log("runned");
	    set_user_url(id);
    	    change_info_for_logged(id);
    }, 2000);
}

function check_all_checkbox(){
    var aa= document.getElementsByTagName("input");
    for (var i =0; i < aa.length; i++){
        if (aa[i].type == 'checkbox')
            aa[i].checked = true;
    }
}
