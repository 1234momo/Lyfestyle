function display_food_log() {
    let breakfast = food_log[1];
    let breakfast_custom = food_log[2];
    let lunch = food_log[3];
    let lunch_custom = food_log[4];
    let dinner = food_log[5];
    let dinner_custom = food_log[6];

    if ((breakfast.length + breakfast_custom.length + lunch.length + lunch_custom.length + dinner.length + dinner_custom.length) == 0) {
        let display_area = document.getElementById("nav-food");

        while (display_area.lastElementChild) {
            display_area.removeChild(display_area.lastElementChild);
        }
        
        let empty_log_msg = document.createElement("p");
        empty_log_msg.innerHTML = "<h2 class='text-center p-5'>The food log looks empty... why not <a href='./addFood.php'>add an entry?</a></h2>";
        display_area.append(empty_log_msg);
    }
    else {
        breakfast = breakfast.split(",");
        breakfast_custom = breakfast_custom.split(",");
        lunch = lunch.split(",");
        lunch_custom = lunch_custom.split(",");
        dinner = dinner.split(",");
        dinner_custom = dinner_custom.split(",");


        if (breakfast == "") {
            let breakfast_area = document.getElementById("Breakfast-area");
            let column_header_area = document.getElementById("breakfast-header");

            while (column_header_area.lastElementChild) {
                column_header_area.removeChild(column_header_area.lastElementChild);
            }

            create_empty_msg(breakfast_area);
        }
        else {
            let name_area = document.getElementById("breakfast-name-area");
            let weight_area = document.getElementById("breakfast-weight-area");

            // Traverse breakfast data
            for (let i = 0; i < breakfast.length- 1; i += 2) {
                let name = breakfast[i];
                let num = breakfast[i+1];
    
                create_food_entry(name, num, name_area, weight_area);
            }
        }

        if (breakfast_custom == "") {
            let breakfast_custom_area = document.getElementById("Breakfast-area-custom");
            let column_header_area = document.getElementById("breakfast-custom-header");

            while (column_header_area.lastElementChild) {
                column_header_area.removeChild(column_header_area.lastElementChild);
            }

            create_empty_msg(breakfast_custom_area);
        }
        else {
            let name_area = document.getElementById("breakfast-custom-name-area");
            let calorie_area = document.getElementById("breakfast-custom-calorie-area");

            // Traverse custom breakfast data
            for (let i = 0; i < breakfast_custom.length- 1; i += 2) {
                let name = breakfast_custom[i];
                let num = breakfast_custom[i+1];
    
                create_food_entry(name, num, name_area, calorie_area);
            }
        }

        if (lunch == "") {
            let lunch_area = document.getElementById("Lunch-area");
            let column_header_area = document.getElementById("lunch-header");

            while (column_header_area.lastElementChild) {
                column_header_area.removeChild(column_header_area.lastElementChild);
            }

            create_empty_msg(lunch_area);
        }
        else {
            let name_area = document.getElementById("lunch-name-area");
            let weight_area = document.getElementById("lunch-weight-area");

            // Traverse lunch data
            for (let i = 0; i < lunch.length- 1; i += 2) {
                let name = lunch[i];
                let num = lunch[i+1];
    
                create_food_entry(name, num, name_area, weight_area);
            }
        }

        if (lunch_custom == "") {
            let lunch_custom_area = document.getElementById("Lunch-area-custom");
            let column_header_area = document.getElementById("lunch-custom-header");

            while (column_header_area.lastElementChild) {
                column_header_area.removeChild(column_header_area.lastElementChild);
            }

            create_empty_msg(lunch_custom_area);
        }
        else {
            let name_area = document.getElementById("lunch-custom-name-area");
            let calorie_area = document.getElementById("lunch-custom-calorie-area");

            // Traverse custom lunch data
            for (let i = 0; i < lunch_custom.length- 1; i += 2) {
                let name = lunch_custom[i];
                let num = lunch_custom[i+1];
    
                create_food_entry(name, num, name_area, calorie_area);
            }
        }

        if (dinner == "") {
            let dinner_area = document.getElementById("Dinner-area");
            let column_header_area = document.getElementById("dinner-header");

            while (column_header_area.lastElementChild) {
                column_header_area.removeChild(column_header_area.lastElementChild);
            }

            create_empty_msg(dinner_area);
        }
        else {
            let name_area = document.getElementById("dinner-name-area");
            let weight_area = document.getElementById("dinner-weight-area");

            // Traverse dinner data
            for (let i = 0; i < dinner.length- 1; i += 2) {
                let name = dinner[i];
                let num = dinner[i+1];
    
                create_food_entry(name, num, name_area, weight_area);
            }
        }

        if (dinner_custom == "") {
            let dinner_custom_area = document.getElementById("Dinner-area-custom");
            let column_header_area = document.getElementById("dinner-custom-header");

            while (column_header_area.lastElementChild) {
                column_header_area.removeChild(column_header_area.lastElementChild);
            }

            create_empty_msg(dinner_custom_area);
        }
        else {
            let name_area = document.getElementById("dinner-custom-name-area");
            let calorie_area = document.getElementById("dinner-custom-calorie-area");

            // Traverse custom dinner data
            for (let i = 0; i < dinner_custom.length- 1; i += 2) {
                let name = dinner_custom[i];
                let num = dinner_custom[i+1];
    
                create_food_entry(name, num, name_area, calorie_area);
            }
        }
    }
}

function create_food_entry(name, num, name_area, number_area) {
    let name_element = document.createElement("input");
    name_element.setAttribute('class', 'form-control col mb-5 text-center shadow');
    name_element.setAttribute('type', 'text');
    name_element.setAttribute('value', name);
    name_element.disabled = true;

    let number_element = document.createElement("input");
    number_element.setAttribute('type', 'number');
    number_element.setAttribute('class', 'form-control col mb-5 text-center shadow');
    number_element.setAttribute('value', num);
    number_element.disabled = true;

    let br = document.createElement("br");

    name_area.appendChild(name_element);
    number_area.appendChild(number_element);
    number_area.appendChild(br);
}

function create_empty_msg(display_area) {
    let msg = document.createElement("h5");
    msg.className="mt-3 mb-5";
    msg.innerHTML = "Nothing eaten this time of day...";

    display_area.appendChild(msg);
}