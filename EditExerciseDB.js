let numOfItems = 0;

function displayExerciseLog() {
    let displayArea = document.getElementById('Exercise-forms');

    exercise_array = exercise_array[1].split(",");
    for (let i = 0; i < exercise_array.length - 1; i += 2) {
        createForm(exercise_array[i], exercise_array[i + 1], displayArea, numOfItems);
    }
}

// Remove a form entry
function removeItem(nameInput, timeInput, removeBtn, br1, br2) {
    // Remove nameInput element
    let elem = document.getElementById(nameInput.id);
    elem.remove();

    // Remove timeInput element
    elem = document.getElementById(timeInput.id);
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
} 

// Creates the components of a form entry
function createForm(itemName, exerciseTime, displayArea, numItem) {
    // <div class="form-row"></div>
    let row_div = document.createElement("div");
    row_div.className = "form-group row justify-content-center";
    
    // <div class="col-4"></div>
    let name_div = document.createElement("div");
    name_div.className = "col-4";

    // Name input
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numItem}`);
    labelElem.setAttribute('class', 'form-control');
    labelElem.setAttribute('id', `item${numItem}nameInput`);
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Exercise name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;

    name_div.appendChild(labelElem);

    // <div class="col-3"></div>
    let time_div = document.createElement("div");
    time_div.className = "col-3";

    // Time input
    let inputTimeElement = document.createElement("input");
    inputTimeElement.setAttribute('type', 'number');
    inputTimeElement.setAttribute('name', `item${numItem}time`);
    inputTimeElement.setAttribute('id', `item${numItem}timeInput`);
    inputTimeElement.setAttribute('class', 'form-control');
    inputTimeElement.setAttribute('placeholder', 'Minutes exercised');
    inputTimeElement.setAttribute('step', '0.1');
    inputTimeElement.setAttribute('min', '0.1');
    inputTimeElement.required = true;
    inputTimeElement.value = exerciseTime;

    time_div.appendChild(inputTimeElement);

    // <div class="col-auto"></div>
    let remove_div = document.createElement("div");
    remove_div.className = "col-auto";

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numItem}Entry`);
    removeEntry.setAttribute('class', 'btn btn-light');
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
                                        `'${linebreakElem2.id}')`);

    // Update the name or time when the user changes anything in the input fields
    inputTimeElement.setAttribute('oninput', `updateTime(this.value, '${inputTimeElement.id}')`);
    labelElem.setAttribute('oninput', `updateName(this.value, '${labelElem.id}')`);

    // Appends the name input, time input, and remove button to the row div 
    row_div.appendChild(name_div);
    row_div.appendChild(time_div);
    row_div.appendChild(remove_div);

    // Appends all the elemnts into the display area
    displayArea.appendChild(row_div);
    displayArea.appendChild(linebreakElem);
    displayArea.appendChild(linebreakElem2);
    
    numOfItems++;
}

// Updates the time input by what the user inputs
function updateTime(newTime, timeInput_id) {
    document.getElementById(timeInput_id).value = newTime;
}

// Updates the name input by what the user inputs
function updateName(newName, nameInput_id) {
    document.getElementById(nameInput_id).value = newName;
}

// Renames the ids of the elements after the removed entry
function renameAttributes(nameInput_id, timeInput_id, removeBtn_id, br1_id, br2_id) {
    let nameInput = document.getElementById(nameInput_id);
    let timeInput = document.getElementById(timeInput_id);
    let removeBtn = document.getElementById(removeBtn_id);
    let br1 = document.getElementById(br1_id);
    let br2 = document.getElementById(br2_id);

    let itemNum = parseInt(br1_id.replace('br1', ''));

    removeItem(nameInput, timeInput, removeBtn, br1, br2);

    for (let i = itemNum + 1; i <= numOfItems; i++) {
        nameInput = document.getElementById(`item${i}nameInput`);
        timeInput = document.getElementById(`item${i}timeInput`);
        removeBtn = document.getElementById(`remove${i}Entry`);
        br1 = document.getElementById(`br1${i}`);
        br2 = document.getElementById(`br2${i}`);

        nameInput.setAttribute('id', `item${i - 1}nameInput`);
        nameInput.setAttribute('name', `item${i - 1}`);
        
        timeInput.setAttribute('id', `item${i - 1}timeInput`);
        timeInput.setAttribute('name', `item${i - 1}time`);
        
        removeBtn.setAttribute('id', `remove${i - 1}Entry`);
        br1.setAttribute('id', `br1${i - 1}`);
        br2.setAttribute('id', `br2${i - 1}`);
    }

    numOfItems -= 1;
}