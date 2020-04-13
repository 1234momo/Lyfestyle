let numOfItemsChosen = 0;

function displayFoods() {
    let displayBreakfastArea = document.getElementById('Breakfast-forms');
    let displayLunchArea = document.getElementById('Lunch-forms');
    let displayDinnerArea = document.getElementById('Dinner-forms');

    breakfast_data = food_array[1].split(",");
    for (let i = 0; i < breakfast_data.length - 1; i += 2) {
        createForm(breakfast_data[i], breakfast_data[i + 1], "Breakfast", displayBreakfastArea);
    }
    
    lunch_data = food_array[2].split(",");
    console.log(lunch_data);
    for (let i = 0; i < lunch_data.length - 1; i += 2) {
        createForm(lunch_data[i], lunch_data[i + 1], "Lunch", displayLunchArea);
    }
    
    dinner_data = food_array[3].split(",");
    for (let i = 0; i < dinner_data.length - 1; i += 2) {
        createForm(dinner_data[i], dinner_data[i + 1], "Dinner", displayDinnerArea);
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

function changePlaces(itemName, itemWeight, numItem) {
    let day_eaten = document.getElementById(`item${numItem}selector`);
    let displayArea = document.getElementById(`${day_eaten.value}-forms`);
    createForm(itemName, itemWeight, day_eaten.value, displayArea);
}

// Creates the components of a form entry from the list of foods
function createForm(itemName, itemWeight, day_eaten, displayArea) {
    // <div class="form-row"></div>
    let row_div = document.createElement("div");
    row_div.className = "form-row";
    
    // <div class="col-auto">
    let name_div = document.createElement("div");
    name_div.className = "col-4";

    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numOfItemsChosen}`);
    labelElem.setAttribute('class', 'form-control');
    labelElem.setAttribute('id', `item${numOfItemsChosen}nameInput`);
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
    inputOZElement.setAttribute('name', `item${numOfItemsChosen}weight`);
    inputOZElement.setAttribute('id', `item${numOfItemsChosen}weightInput`);
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
    dayEatenElem.setAttribute('name', `item${numOfItemsChosen}dayeaten`);
    dayEatenElem.setAttribute('id', `item${numOfItemsChosen}selector`);
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
    removeEntry.setAttribute('id', `remove${numOfItemsChosen}Entry`);
    removeEntry.setAttribute('class', 'btn btn-light');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    remove_div.appendChild(removeEntry);

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numOfItemsChosen}`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numOfItemsChosen}`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `removeItem(item${numOfItemsChosen}nameInput,` + 
                                        `item${numOfItemsChosen}weightInput,` + 
                                        `item${numOfItemsChosen}selector,` + 
                                        `remove${numOfItemsChosen}Entry,` +
                                        `br1${numOfItemsChosen},` +
                                        `br2${numOfItemsChosen})`);

    dayEatenElem.setAttribute('onChange', `changePlaces('${itemName}',` +
                                          `${itemWeight},` +
                                          `${numOfItemsChosen});` +
                                          `removeItem(item${numOfItemsChosen}nameInput,` + 
                                          `item${numOfItemsChosen}weightInput,` + 
                                          `item${numOfItemsChosen}selector,` + 
                                          `remove${numOfItemsChosen}Entry,` +
                                          `br1${numOfItemsChosen},` +
                                          `br2${numOfItemsChosen})`);
                                        
                                        
    // Appends the entry components to the entry area
    // row_div.appendChild(labelElem);
    // row_div.appendChild(inputOZElement);
    // row_div.appendChild(dayEatenElem);
    // row_div.appendChild(removeEntry);
    // row_div.appendChild(linebreakElem);
    // row_div.appendChild(linebreakElem2);

    row_div.appendChild(name_div);
    row_div.appendChild(weight_div);
    row_div.appendChild(selector_div);
    row_div.appendChild(remove_div);
                                        
    displayArea.appendChild(row_div);
    displayArea.appendChild(linebreakElem);
    displayArea.appendChild(linebreakElem2);
    
    numOfItemsChosen++;
}