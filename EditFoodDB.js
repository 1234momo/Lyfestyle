let numOfChosenItems = 0;
let numOfCustomItems = 0;

function displayFoods() {
    let displayBreakfastArea = document.getElementById('Breakfast-forms');
    let displayLunchArea = document.getElementById('Lunch-forms');
    let displayDinnerArea = document.getElementById('Dinner-forms');
    let displayCustomBreakfastArea = document.getElementById('Breakfast-forms-custom');
    let displayCustomLunchArea = document.getElementById('Lunch-forms-custom');
    let displayCustomDinnerArea = document.getElementById('Dinner-forms-custom');

    let breakfast_data = food_array[1].split(",");
    for (let i = 0; i < breakfast_data.length - 1; i += 2) {
        createForm(breakfast_data[i], breakfast_data[i + 1], "Breakfast", displayBreakfastArea, -1);
    }
    
    let lunch_data = food_array[3].split(",");
    for (let i = 0; i < lunch_data.length - 1; i += 2) {
        createForm(lunch_data[i], lunch_data[i + 1], "Lunch", displayLunchArea, -1);
    }
    
    let dinner_data = food_array[5].split(",");
    for (let i = 0; i < dinner_data.length - 1; i += 2) {
        createForm(dinner_data[i], dinner_data[i + 1], "Dinner", displayDinnerArea, -1);
    }

    breakfast_data = food_array[2].split(",");
    for (let i = 0; i < breakfast_data.length - 1; i += 2) {
        createCustomForm(breakfast_data[i], breakfast_data[i + 1], "Breakfast", displayCustomBreakfastArea, -1);
    }
    
    lunch_data = food_array[4].split(",");
    for (let i = 0; i < lunch_data.length - 1; i += 2) {
        createCustomForm(lunch_data[i], lunch_data[i + 1], "Lunch", displayCustomLunchArea, -1);
    }
    
    dinner_data = food_array[6].split(",");
    for (let i = 0; i < dinner_data.length - 1; i += 2) {
        createCustomForm(dinner_data[i], dinner_data[i + 1], "Dinner", displayCustomDinnerArea, -1);
    }
}

// Remove a form entry
function removeItem(nameInput, weightInput, selector, removeBtn, br1, br2, isFromList, isChangingPlaces) {
    // Remove nameInput element
    let elem = document.getElementById(nameInput.id);
    elem.remove();

    // Remove timeInput element
    elem = document.getElementById(weightInput.id);
    elem.remove();

    // Remove the drop down list (Breakfast, Lunch, Dinner) element
    elem = document.getElementById(selector.id);
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

    // Decrement count of chosen items if elements are from list and aren't changing places
    if (isFromList && !isChangingPlaces) { 
        --numOfChosenItems; 
    }
    // Decrement count of custom items if elements aren't from list and aren't changing places 
    else if (!isFromList && !isChangingPlaces){ 
        --numOfCustomItems; 
    }
} 

function changePlaces(label, numInput, selector, removeEntry, linebreak1, linebreak2, isFromList) {
    let nameInput = document.getElementById(label);
    let itemName = nameInput.value;

    let weightInput = document.getElementById(numInput);
    let itemWeight = weightInput.value;

    let dayEatenSelector = document.getElementById(selector);
    let dayEaten = dayEatenSelector.value;

    let removeBtn = document.getElementById(removeEntry);
    let br1 = document.getElementById(linebreak1);
    let br2 = document.getElementById(linebreak2);

    let displayArea = isFromList ? document.getElementById(`${dayEaten}-forms`) : document.getElementById(`${dayEaten}-forms-custom`);
    let numItem = 0;
    if (isFromList) {
        numItem = linebreak1.replace('br1', '');
    }
    else {
        numItem = linebreak1.replace('br1', '');
        numItem = numItem.replace('custom', '');
    }

    removeItem(nameInput, weightInput, dayEatenSelector, removeBtn, br1, br2, isFromList, true);
    isFromList ? createForm(itemName, itemWeight, dayEaten, displayArea, numItem) : createCustomForm(itemName, itemWeight, dayEaten, displayArea, numItem);
}

// Creates the components of a form entry from the list of foods
function createForm(itemName, itemWeight, day_eaten, displayArea, numItem) {
    let itemNum = 0;

    // If numItem is -1, we are loading the page so just post the info from the DB
    // else, a form item's position is changing
    if (numItem == -1) {
        itemNum = numOfChosenItems;
    }
    else {
        itemNum = numItem;
    }

    // <div class="form-row"></div>
    let row_div = document.createElement("div");
    row_div.className = "form-row";
    
    // <div class="col-auto">
    let name_div = document.createElement("div");
    name_div.className = "col-5";

    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${itemNum}`);
    labelElem.setAttribute('id', `item${itemNum}`);
    labelElem.setAttribute('class', 'form-control');
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Food name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;
    labelElem.readOnly = true;

    name_div.appendChild(labelElem);

    // <div class="col-auto">
    let weight_div = document.createElement("div");
    weight_div.className = "col-2";

    // Weight of food input
    let inputOZElement = document.createElement("input");
    inputOZElement.setAttribute('type', 'number');
    inputOZElement.setAttribute('name', `item${itemNum}weight`);
    inputOZElement.setAttribute('id', `item${itemNum}weight`);
    inputOZElement.setAttribute('class', 'form-control');
    inputOZElement.setAttribute('placeholder', 'Weight');
    inputOZElement.setAttribute('step', '0.01');
    inputOZElement.setAttribute('min', '0.01');
    inputOZElement.required = true;
    inputOZElement.value = itemWeight;

    weight_div.appendChild(inputOZElement);

    // <div class="col-auto">
    let selector_div = document.createElement("div");
    selector_div.className = "col-auto";

    // Breakfast, Lunch, and Dinner selector
    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `item${itemNum}selector`);
    dayEatenElem.setAttribute('id', `item${itemNum}selector`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);
    dayEatenElem.value = day_eaten;

    selector_div.appendChild(dayEatenElem);

    // <div class="col-auto">
    let remove_div = document.createElement("div");
    remove_div.className = "col-auto";

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${itemNum}Entry`);
    removeEntry.setAttribute('class', 'btn btn-light');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    remove_div.appendChild(removeEntry);

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${itemNum}`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${itemNum}`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `renameAttributes('${labelElem.id}',` +
                                        `'${inputOZElement.id}',` +
                                        `'${dayEatenElem.id}',` +
                                        `'${removeEntry.id}',` +
                                        `'${linebreakElem.id}',` +
                                        `'${linebreakElem2.id}',` + 
                                        `true)`);

    inputOZElement.setAttribute('oninput', `updateSecondInput(this.value, '${inputOZElement.id}')`);

    dayEatenElem.setAttribute('onChange', `changePlaces('${labelElem.id}',` +
                                            `'${inputOZElement.id}',` +
                                            `'${dayEatenElem.id}',` +
                                            `'${removeEntry.id}',` +
                                            `'${linebreakElem.id}',` +
                                            `'${linebreakElem2.id}',` +
                                            `true)`);

    row_div.appendChild(name_div);
    row_div.appendChild(weight_div);
    row_div.appendChild(selector_div);
    row_div.appendChild(remove_div);
                                        
    displayArea.appendChild(row_div);
    displayArea.appendChild(linebreakElem);
    displayArea.appendChild(linebreakElem2);
    
    if (numItem == -1) {
        ++numOfChosenItems;
    }
}

// Creates the components of a form entry from the list of foods
function createCustomForm(itemName, itemWeight, day_eaten, displayArea, numItem) {
    let itemNum = 0;

    // If numItem is -1, we are loading the page so just post the info from the DB
    // else, a form item's position is changing
    if (numItem == -1) {
        itemNum = numOfCustomItems;
    }
    else {
        itemNum = numItem;
    }

    // <div class="form-row"></div>
    let row_div = document.createElement("div");
    row_div.className = "form-row";
    
    // <div class="col-auto">
    let name_div = document.createElement("div");
    name_div.className = "col-5";

    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${itemNum}custom`);
    labelElem.setAttribute('id', `item${itemNum}custom`);
    labelElem.setAttribute('class', 'form-control');
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Food name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;

    name_div.appendChild(labelElem);

    // <div class="col-auto">
    let calories_div = document.createElement("div");
    calories_div.className = "col-2";

    // Calories of food input
    let caloriesInputElement = document.createElement("input");
    caloriesInputElement.setAttribute('type', 'number');
    caloriesInputElement.setAttribute('name', `item${itemNum}calories`);
    caloriesInputElement.setAttribute('id', `item${itemNum}calories`);
    caloriesInputElement.setAttribute('class', 'form-control');
    caloriesInputElement.setAttribute('placeholder', 'Calories eaten');
    caloriesInputElement.setAttribute('step', '0.01');
    caloriesInputElement.setAttribute('min', '0.01');
    caloriesInputElement.required = true;
    caloriesInputElement.value = itemWeight;

    calories_div.appendChild(caloriesInputElement);

    // <div class="col-auto">
    let selector_div = document.createElement("div");
    selector_div.className = "col-auto";

    // Breakfast, Lunch, and Dinner selector
    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `item${itemNum}customSelector`);
    dayEatenElem.setAttribute('id', `item${itemNum}customSelector`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);
    dayEatenElem.value = day_eaten;

    selector_div.appendChild(dayEatenElem);

    // <div class="col-auto">
    let remove_div = document.createElement("div");
    remove_div.className = "col-auto";

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${itemNum}customEntry`);
    removeEntry.setAttribute('class', 'btn btn-light');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    remove_div.appendChild(removeEntry);

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${itemNum}custom`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${itemNum}custom`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `renameAttributes('${labelElem.id}',` +
                                        `'${caloriesInputElement.id}',` +
                                        `'${dayEatenElem.id}',` +
                                        `'${removeEntry.id}',` +
                                        `'${linebreakElem.id}',` +
                                        `'${linebreakElem2.id}',` +
                                        `false)`);

    caloriesInputElement.setAttribute('oninput', `updateSecondInput(this.value, '${caloriesInputElement.id}')`);
    labelElem.setAttribute('oninput', `updateName(this.value, '${labelElem.id}')`);

    dayEatenElem.setAttribute('onChange', `changePlaces('${labelElem.id}',` +
                                            `'${caloriesInputElement.id}',` +
                                            `'${dayEatenElem.id}',` +
                                            `'${removeEntry.id}',` +
                                            `'${linebreakElem.id}',` +
                                            `'${linebreakElem2.id}',` +
                                            `false)`);

    row_div.appendChild(name_div);
    row_div.appendChild(calories_div);
    row_div.appendChild(selector_div);
    row_div.appendChild(remove_div);
                                        
    displayArea.appendChild(row_div);
    displayArea.appendChild(linebreakElem);
    displayArea.appendChild(linebreakElem2);
    
    if (numItem == -1) {
        ++numOfCustomItems;
    }
}

function updateSecondInput(newNum, secondElement_id) {
    document.getElementById(secondElement_id).value = newNum;
}

function updateName(newName, nameElement_id) {
    document.getElementById(nameElement_id).value = newName;
}

// Renames the consecutive ids and names of each element after the elements that are being deleted
function renameAttributes(nameInput_id, secondInput_id, dayEatenSelector_id, removeBtn_id, br1_id, br2_id, isFromList) {
    let nameInput = document.getElementById(nameInput_id);
    let secondInput = document.getElementById(secondInput_id);
    let dayEatenSelector = document.getElementById(dayEatenSelector_id);
    let removeBtn = document.getElementById(removeBtn_id);
    let br1 = document.getElementById(br1_id);
    let br2 = document.getElementById(br2_id);

    // Retrieves the elements' index
    let itemNum;
    if (isFromList) {
        itemNum = parseInt(br1_id.replace('br1', ''));
    }
    else {
        itemNum = br1_id.replace('br1', '');
        itemNum = parseInt(itemNum.replace('custom', ''));
    }

    removeItem(nameInput, secondInput, dayEatenSelector, removeBtn, br1, br2, isFromList, false);

    let numOfItems = isFromList ? numOfChosenItems : numOfCustomItems;

    // Loop through the elements after the current element's index
    for (let i = itemNum + 1; i <= numOfItems; ++i) {
        // Find the elements of the ith index
        nameInput = isFromList ? document.getElementById(`item${i}`) : document.getElementById(`item${i}custom`);
        secondInput = isFromList ? document.getElementById(`item${i}weight`) : document.getElementById(`item${i}calories`);
        dayEatenSelector = isFromList ? document.getElementById(`item${i}selector`) : document.getElementById(`item${i}customSelector`);
        removeBtn = isFromList ? document.getElementById(`remove${i}Entry`) : document.getElementById(`remove${i}customEntry`);
        br1 = isFromList ? document.getElementById(`br1${i}`) : document.getElementById(`br1${i}custom`);
        br2 = isFromList ? document.getElementById(`br2${i}`) : document.getElementById(`br2${i}custom`);

        // Rename ids and names
        if (isFromList) {
            nameInput.setAttribute('id', `item${i - 1}`);
            nameInput.setAttribute('name', `item${i - 1}`);
            secondInput.setAttribute('id', `item${i - 1}weight`);
            secondInput.setAttribute('name', `item${i - 1}weight`);
            dayEatenSelector.setAttribute('id', `item${i - 1}selector`);
            dayEatenSelector.setAttribute('name', `item${i - 1}selector`);
            removeBtn.setAttribute('id', `remove${i - 1}Entry`);
            br1.setAttribute('id', `br1${i - 1}`);
            br2.setAttribute('id', `br2${i - 1}`);
        }
        else {
            nameInput.setAttribute('id', `item${i - 1}custom`);
            nameInput.setAttribute('name', `item${i - 1}custom`);
            secondInput.setAttribute('id', `item${i - 1}calories`);
            secondInput.setAttribute('name', `item${i - 1}calories`);
            dayEatenSelector.setAttribute('id', `item${i - 1}customSelector`);
            dayEatenSelector.setAttribute('name', `item${i - 1}customSelector`);
            removeBtn.setAttribute('id', `remove${i - 1}customEntry`);
            br1.setAttribute('id', `br1${i - 1}custom`);
            br2.setAttribute('id', `br2${i - 1}custom`);
        }

        // Rename the onClick to the appropriate ith index
        if (isFromList) {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}',` + 
                                              `'item${i - 1}weight',` + 
                                              `'item${i - 1}selector',` + 
                                              `'remove${i - 1}Entry',` +
                                              `'br1${i - 1}',` +
                                              `'br2${i - 1}',` +
                                              `true)`);
            dayEatenSelector.setAttribute('onChange', `changePlaces('item${i - 1}',` +
                                                      `'item${i - 1}weight',` +
                                                      `'item${i - 1}selector',` +
                                                      `'remove${i - 1}Entry',` +
                                                      `'br1${i - 1}',` +
                                                      `'br2${i - 1}',` +
                                                      `true)`);
            secondInput.setAttribute('oninput', `updateSecondInput(this.value, '${secondInput.id}')`);
        }
        else {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}custom',` + 
                                              `'item${i - 1}calories',` + 
                                              `'item${i - 1}customSelector',` + 
                                              `'remove${i - 1}customEntry',` +
                                              `'br1${i - 1}custom',` +
                                              `'br2${i - 1}custom',` +
                                              `false)`);
            dayEatenSelector.setAttribute('onChange', `changePlaces('item${i - 1}custom',` +
                                                      `'item${i - 1}calories',` +
                                                      `'item${i - 1}customSelector',` +
                                                      `'remove${i - 1}customEntry',` +
                                                      `'br1${i - 1}custom',` +
                                                      `'br2${i - 1}custom',` +
                                                      `false)`);
            secondInput.setAttribute('oninput', `updateSecondInput(this.value, '${secondInput.id}')`);
            nameInput.setAttribute('oninput', `updateName(this.value, '${nameInput.id}')`);
        }
    }
}