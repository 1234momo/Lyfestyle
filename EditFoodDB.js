let numOfItemsChosen = 0;

function displayFoods() {
    let displayBreakfastArea = document.getElementById('Breakfast-forms');
    let displayLunchArea = document.getElementById('Lunch-forms');
    let displayDinnerArea = document.getElementById('Dinner-forms');

    breakfast_data = food_array[1].split(",");
    for (let i = 0; i < breakfast_data.length - 1; i += 2) {
        createForm(breakfast_data[i], breakfast_data[i + 1], "Breakfast", displayBreakfastArea, -1);
    }
    
    lunch_data = food_array[2].split(",");
    for (let i = 0; i < lunch_data.length - 1; i += 2) {
        createForm(lunch_data[i], lunch_data[i + 1], "Lunch", displayLunchArea, -1);
    }
    
    dinner_data = food_array[3].split(",");
    for (let i = 0; i < dinner_data.length - 1; i += 2) {
        createForm(dinner_data[i], dinner_data[i + 1], "Dinner", displayDinnerArea, -1);
    }
}

// Remove a form entry
function removeItem(nameInput, weightInput, selector, removeBtn, br1, br2) {
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
} 

function changePlaces(label, numInput, selector, removeEntry, linebreak1, linebreak2) {
    let nameInput = document.getElementById(label);
    let itemName = nameInput.value;

    let weightInput = document.getElementById(numInput);
    let itemWeight = weightInput.value;

    let dayEatenSelector = document.getElementById(selector);
    let dayEaten = dayEatenSelector.value;

    let removeBtn = document.getElementById(removeEntry);
    let br1 = document.getElementById(linebreak1);
    let br2 = document.getElementById(linebreak2);

    let displayArea = document.getElementById(`${dayEaten}-forms`);
    let numItem = linebreak1.replace('br1', '');

    removeItem(nameInput, weightInput, dayEatenSelector, removeBtn, br1, br2);
    createForm(itemName, itemWeight, dayEaten, displayArea, numItem);
}

// Creates the components of a form entry from the list of foods
function createForm(itemName, itemWeight, day_eaten, displayArea, numItem) {
    let itemNum = 0;

    // If numItem is -1, we loading the page so post info from the DB
    // else, a form item's position is changing
    if (numItem == -1) {
        itemNum = numOfItemsChosen;
    }
    else {
        itemNum = numItem;
    }

    // <div class="form-row"></div>
    let row_div = document.createElement("div");
    row_div.className = "form-row";
    
    // <div class="col-auto">
    let name_div = document.createElement("div");
    name_div.className = "col-4";

    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${itemNum}`);
    labelElem.setAttribute('class', 'form-control');
    labelElem.setAttribute('id', `item${itemNum}nameInput`);
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Food name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;

    name_div.appendChild(labelElem);

    // <div class="col-auto">
    let weight_div = document.createElement("div");
    weight_div.className = "col-3";

    // Weight of food input
    let inputOZElement = document.createElement("input");
    inputOZElement.setAttribute('type', 'number');
    inputOZElement.setAttribute('name', `item${itemNum}weight`);
    inputOZElement.setAttribute('id', `item${itemNum}weightInput`);
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
    dayEatenElem.setAttribute('name', `item${itemNum}dayeaten`);
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
                                        `'${linebreakElem2.id}')`);

    inputOZElement.setAttribute('oninput', `updateWeight(this.value, '${inputOZElement.id}')`);
    labelElem.setAttribute('oninput', `updateWeight(this.value, '${labelElem.id}')`);

    dayEatenElem.setAttribute('onChange', `changePlaces('${labelElem.id}',` +
                                          `'${inputOZElement.id}',` +
                                          `'${dayEatenElem.id}',` +
                                          `'${removeEntry.id}',` +
                                          `'${linebreakElem.id}',` +
                                          `'${linebreakElem2.id}')`);

    row_div.appendChild(name_div);
    row_div.appendChild(weight_div);
    row_div.appendChild(selector_div);
    row_div.appendChild(remove_div);
                                        
    displayArea.appendChild(row_div);
    displayArea.appendChild(linebreakElem);
    displayArea.appendChild(linebreakElem2);
    
    if (numItem == -1) {
        numOfItemsChosen++;
    }
}

function updateWeight(newWeight, weightElement_id) {
    document.getElementById(weightElement_id).value = newWeight;
}

function updateName(newName, nameElement_id) {
    document.getElementById(nameElement_id).value = newName;
}

function renameAttributes(label, numInput, selector, removeEntry, linebreak1, linebreak2) {
    let nameInput = document.getElementById(label);
    let weightInput = document.getElementById(numInput);
    let dayEatenSelector = document.getElementById(selector);
    let removeBtn = document.getElementById(removeEntry);
    let br1 = document.getElementById(linebreak1);
    let br2 = document.getElementById(linebreak2);

    let itemNum = parseInt(linebreak1.replace('br1', ''));

    console.log(itemNum);

    removeItem(nameInput, weightInput, dayEatenSelector, removeBtn, br1, br2);

    for (let i = itemNum + 1; i <= numOfItemsChosen; i++) {
        console.log(i);
        nameInput = document.getElementById(`item${i}nameInput`);
        weightInput = document.getElementById(`item${i}weightInput`);
        dayEatenSelector = document.getElementById(`item${i}selector`);
        removeBtn = document.getElementById(`remove${i}Entry`);
        br1 = document.getElementById(`br1${i}`);
        br2 = document.getElementById(`br2${i}`);

        nameInput.setAttribute('id', `item${i - 1}nameInput`);
        nameInput.setAttribute('name', `item${i - 1}`);
        
        weightInput.setAttribute('id', `item${i - 1}weightInput`);
        weightInput.setAttribute('name', `item${i - 1}weight`);

        dayEatenSelector.setAttribute('id', `item${i - 1}selector`);       
        dayEatenSelector.setAttribute('name', `item${i - 1}dayeaten`);
        
        removeBtn.setAttribute('id', `remove${i - 1}Entry`);
        br1.setAttribute('id', `br1${i - 1}`);
        br2.setAttribute('id', `br2${i - 1}`);
        console.log(nameInput.id);
    }

    numOfItemsChosen -= 1;
}