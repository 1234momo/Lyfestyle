let numOfChosenItems = 0;
let numOfCustomItems = 0;

function displayExerciseLog() {
    let displayListArea = document.getElementById('Exercise-forms');
    let displayCustomArea = document.getElementById('Exercise-forms-custom');

    exercise_array_list = exercise_array[1].split(",");
    for (let i = 0; i < exercise_array_list.length - 1; i += 2) {
        createForm(exercise_array_list[i], exercise_array_list[i + 1], displayListArea, numOfChosenItems);
    }
    
    exercise_array_custom = exercise_array[2].split(",");
    for (let i = 0; i < exercise_array_custom.length - 1; i += 2) {
        createCustomForm(exercise_array_custom[i], exercise_array_custom[i + 1], displayCustomArea, numOfCustomItems);
    }
}

// Remove a form entry
function removeItem(nameInput, secondInput, removeBtn, br1, br2, isFromList) {
    // Remove nameInput element
    let elem = document.getElementById(nameInput.id);
    elem.remove();

    // Remove timeInput element
    elem = document.getElementById(secondInput.id);
    elem.remove();

    // Remove the remove button element
    elem = document.getElementById(removeBtn.id);
    elem.remove();

    // Remove first line break
    elem = document.getElementById(br1.id);
    elem.remove();

    // Remove second line break
    elem = document.getElementById(br2.id);
    elem.remove();

    if (isFromList) {
        --numOfChosenItems;
    }
    else {
        -- numOfCustomItems;
    }
} 

// Creates the components of a form entry for list inputs
function createForm(itemName, exerciseTime, displayArea, numItem) {
    // <div class="form-row"></div>
    let row_div = document.createElement("div");
    row_div.className = "form-group row justify-content-center mb-0";
    
    // <div class="col-4"></div>
    let name_div = document.createElement("div");
    name_div.className = "col-5 p-0";

    // Name input
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numItem}`);
    labelElem.setAttribute('id', `item${numItem}`);
    labelElem.setAttribute('class', 'form-control shadow');
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Exercise name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;
    labelElem.readOnly = true;

    name_div.appendChild(labelElem);

    // <div class="col-3"></div>
    let time_div = document.createElement("div");
    time_div.className = "col-4 p-0 ml-3";

    // Time input
    let inputTimeElement = document.createElement("input");
    inputTimeElement.setAttribute('type', 'number');
    inputTimeElement.setAttribute('name', `item${numItem}time`);
    inputTimeElement.setAttribute('id', `item${numItem}time`);
    inputTimeElement.setAttribute('class', 'form-control shadow');
    inputTimeElement.setAttribute('placeholder', 'Minutes exercised');
    inputTimeElement.setAttribute('step', '0.01');
    inputTimeElement.setAttribute('min', '0.01');
    inputTimeElement.required = true;
    inputTimeElement.value = exerciseTime;

    time_div.appendChild(inputTimeElement);

    // <div class="col-auto"></div>
    let remove_div = document.createElement("div");
    remove_div.className = "col-auto p-0 ml-3";

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numItem}Entry`);
    removeEntry.setAttribute('class', 'btn btn-outline-danger');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    remove_div.appendChild(removeEntry);

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numItem}`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numItem}`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `renameAttributes('${labelElem.id}',` +
                                        `'${inputTimeElement.id}',` +
                                        `'${removeEntry.id}',` +
                                        `'${linebreakElem.id}',` +
                                        `'${linebreakElem2.id}',` +
                                        `true)`);

    // Update the name or time when the user changes anything in the input fields
    inputTimeElement.setAttribute('oninput', `updateSecondInput(this.value, '${inputTimeElement.id}')`);

    // Appends the name input, time input, and remove button to the row div 
    row_div.appendChild(name_div);
    row_div.appendChild(time_div);
    row_div.appendChild(remove_div);

    // Appends all the elemnts into the display area
    displayArea.appendChild(row_div);
    displayArea.appendChild(linebreakElem);
    displayArea.appendChild(linebreakElem2);
    
    ++numOfChosenItems;
}

// Creates the components of a form entry for custom inputs
function createCustomForm(itemName, calories, displayArea, numItem) {
    // <div class="form-row"></div>
    let row_div = document.createElement("div");
    row_div.className = "form-group row justify-content-center mb-0";
    
    // <div class="col-4"></div>
    let name_div = document.createElement("div");
    name_div.className = "col-5 p-0";

    // Name input
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numItem}customName`);
    labelElem.setAttribute('id', `item${numItem}customName`);
    labelElem.setAttribute('class', 'form-control shadow');
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Exercise name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;

    name_div.appendChild(labelElem);

    // <div class="col-3"></div>
    let calorie_div = document.createElement("div");
    calorie_div.className = "col-4 p-0 ml-3";

    // Calorie input
    let calorieElement = document.createElement("input");
    calorieElement.setAttribute('type', 'number');
    calorieElement.setAttribute('name', `item${numItem}calories`);
    calorieElement.setAttribute('id', `item${numItem}calories`);
    calorieElement.setAttribute('class', 'form-control shadow');
    calorieElement.setAttribute('placeholder', 'Calories burned');
    calorieElement.setAttribute('step', '0.01');
    calorieElement.setAttribute('min', '0.01');
    calorieElement.required = true;
    calorieElement.value = calories;

    calorie_div.appendChild(calorieElement);

    // <div class="col-auto"></div>
    let remove_div = document.createElement("div");
    remove_div.className = "col-auto p-0 ml-3";

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numItem}customEntry`);
    removeEntry.setAttribute('class', 'btn btn-outline-danger shadow');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    remove_div.appendChild(removeEntry);

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numItem}custom`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numItem}custom`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `renameAttributes('${labelElem.id}',` +
                                        `'${calorieElement.id}',` +
                                        `'${removeEntry.id}',` +
                                        `'${linebreakElem.id}',` +
                                        `'${linebreakElem2.id}',` +
                                        `false)`);

    // Update the name or calories when the user changes anything in the input fields
    calorieElement.setAttribute('oninput', `updateSecondInput(this.value, '${calorieElement.id}')`);
    labelElem.setAttribute('oninput', `updateName(this.value, '${labelElem.id}')`);

    // Appends the name input, time input, and remove button to the row div 
    row_div.appendChild(name_div);
    row_div.appendChild(calorie_div);
    row_div.appendChild(remove_div);

    // Appends all the elemnts into the display area
    displayArea.appendChild(row_div);
    displayArea.appendChild(linebreakElem);
    displayArea.appendChild(linebreakElem2);
    
    ++numOfCustomItems;
}

// Updates the time input by what the user inputs
function updateSecondInput(newValue, input_id) {
    document.getElementById(input_id).value = newValue;
    console.log(input_id + ": " + document.getElementById(input_id).value);
}

// Updates the name input by what the user inputs
function updateName(newName, nameInput_id) {
    document.getElementById(nameInput_id).value = newName;
}

// Renames the ids of the elements after the removed entry
function renameAttributes(nameInput_id, secondInput_id, removeBtn_id, br1_id, br2_id, isFromList) {
    let nameInput = document.getElementById(nameInput_id);
    let secondInput = document.getElementById(secondInput_id);
    let removeBtn = document.getElementById(removeBtn_id);
    let br1 = document.getElementById(br1_id);
    let br2 = document.getElementById(br2_id);

    // Retrieve the elements' index
    let itemNum = 0; 
    if (isFromList) {
        itemNum = parseInt(br1_id.replace('br1', ''));
    }
    else {
        itemNum = br1_id.replace('br1', '');
        itemNum = parseInt(itemNum.replace('custom', ''));
    }

    removeItem(nameInput, secondInput, removeBtn, br1, br2, isFromList);
    let numOfItems = isFromList ? numOfChosenItems : numOfCustomItems;

    // Loop through the elements after the current element's index
    for (let i = itemNum + 1; i <= numOfItems; ++i) {
        // Find the elements of the ith index
        nameInput = isFromList ? document.getElementById(`item${i}`) : document.getElementById(`item${i}customName`);
        secondInput = isFromList ? document.getElementById(`item${i}time`) : document.getElementById(`item${i}calories`);
        removeBtn = isFromList ? document.getElementById(`remove${i}Entry`) : document.getElementById(`remove${i}customEntry`);
        br1 = isFromList ? document.getElementById(`br1${i}`) : document.getElementById(`br1${i}custom`);
        br2 = isFromList ? document.getElementById(`br2${i}`) : document.getElementById(`br2${i}custom`);

        // Rename ids and names
        if (isFromList) {
            nameInput.setAttribute('id', `item${i - 1}`);
            nameInput.setAttribute('name', `item${i - 1}`);
            secondInput.setAttribute('id', `item${i - 1}time`);
            secondInput.setAttribute('name', `item${i - 1}time`);
            removeBtn.setAttribute('id', `remove${i - 1}Entry`);
            br1.setAttribute('id', `br1${i - 1}`);
            br2.setAttribute('id', `br2${i - 1}`);
        }
        else {
            nameInput.setAttribute('id', `item${i - 1}customName`);
            nameInput.setAttribute('name', `item${i - 1}customName`);
            secondInput.setAttribute('id', `item${i - 1}calories`);
            secondInput.setAttribute('name', `item${i - 1}calories`);
            removeBtn.setAttribute('id', `remove${i - 1}customEntry`);
            br1.setAttribute('id', `br1${i - 1}custom`);
            br2.setAttribute('id', `br2${i - 1}custom`);
        }

        // Rename the onClick to the appropriate ith index
        if (isFromList) {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}',` + 
                                              `'item${i - 1}time',` + 
                                              `'remove${i - 1}Entry',` +
                                              `'br1${i - 1}',` +
                                              `'br2${i - 1}',` +
                                              `true)`);
            secondInput.setAttribute('oninput', `updateSecondInput(this.value, '${secondInput.id}')`);

        }
        else {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}customName',` + 
                                              `'item${i - 1}calories',` + 
                                              `'remove${i - 1}customEntry',` +
                                              `'br1${i - 1}custom',` +
                                              `'br2${i - 1}custom',` +
                                              `false)`);
            secondInput.setAttribute('oninput', `updateSecondInput(this.value, '${secondInput.id}')`);
            nameInput.setAttribute('oninput', `updateName(this.value, '${nameInput.id}')`);
        }
    }
}