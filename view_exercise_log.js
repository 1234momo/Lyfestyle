function display_exercise_log() {
    let workout = exercise_log[1];
    let workout_custom = exercise_log[2];

    if ((workout.length + workout_custom.length) == 0) {
        let display_area = document.getElementById("nav-exercise");

        while (display_area.lastElementChild) {
            display_area.removeChild(display_area.lastElementChild);
        }
        
        let empty_log_msg = document.createElement("p");
        empty_log_msg.innerHTML = "<h2 class='text-center p-5'>The exercise log looks empty... why not <a href='./addExercise.php'>add an entry?</a></h2>";
        display_area.append(empty_log_msg);
    }
    else {
        workout = workout.split(",");
        workout_custom = workout_custom.split(",");

        let workout_area = document.getElementById("Exercise-area");
        let workout_custom_area = document.getElementById("Exercise-area-custom");
        
        if (workout == "") {
            let workout_col_header = document.getElementById("workout-header");

            while (workout_col_header.lastElementChild) {
                workout_col_header.removeChild(workout_col_header.lastElementChild);
            }

            create_empty_msg_exercise(workout_area);
        }
        else {
            let name_area = document.getElementById("name-area");
            let time_area = document.getElementById("time-area");

            // Traverse workout data
            for (let i = 0; i < workout.length - 1; i += 2) {
                let name = workout[i];
                let num = workout[i+1];
    
                create_exercise_entry(name, num, name_area, time_area);
            }
        }

        if (workout_custom == "") {
            let workout_col_header = document.getElementById("workout-custom-header");

            while (workout_col_header.lastElementChild) {
                workout_col_header.removeChild(workout_col_header.lastElementChild);
            }

            create_empty_msg_exercise(workout_custom_area);
        }
        else {
            let name_area = document.getElementById("custom-name-area");
            let calorie_area = document.getElementById("custom-calorie-area");

            // Traverse custom workout data
            for (let i = 0; i < workout_custom.length - 1; i += 2) {
                let name = workout_custom[i];
                let num = workout_custom[i+1];

                create_exercise_entry(name, num, name_area, calorie_area);
            }
        }
    }
}

function create_exercise_entry(name, num, name_area, number_area) {
    let name_element = document.createElement("input");
    name_element.setAttribute('class', 'form-control col-auto mx-5 mb-5 text-center shadow');
    name_element.setAttribute('type', 'text');
    name_element.setAttribute('value', name);
    name_element.disabled = true;

    let number_element = document.createElement("input");
    number_element.setAttribute('type', 'number');
    number_element.setAttribute('class', 'form-control col-auto mb-5 text-center shadow');
    number_element.setAttribute('value', num);
    number_element.disabled = true;

    let br = document.createElement("br");

    name_area.appendChild(name_element);
    number_area.appendChild(number_element);
    number_area.appendChild(br)
}

function create_empty_msg_exercise(display_area) {
    let msg = document.createElement("h5");
    msg.className = "mt-3 text-center";
    msg.innerHTML = "No exercise input yet...";

    display_area.appendChild(msg);
}