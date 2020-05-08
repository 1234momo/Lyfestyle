let numOfItemsChosen = 0;
let numOfCustomItems = 0;
let db_json;
let itemsChosen = [];

function showFoods(e) {
    // Prevent page from reloading
    e.preventDefault();

    // Call json file with foods and then display the data
    fetch("../database/food_database.json")
    .then(function (response) {
        return response.json();
    })
    .then(function (data) {
        displayData(data);
        db_json = data;
    })
    .catch(function (err) {
        console.log(err);
    });
}

// Display all the foods in the json file
function displayData(data) {
    let searchTextBox = document.getElementById("keyword");
    let queryItem = searchTextBox.value;
    queryItem = queryItem.trim().toLowerCase();
    let mainContainer = document.getElementById("search-list");

    // Clear any previous search result
    while (mainContainer.firstChild) {
        mainContainer.removeChild(mainContainer.firstChild);
    }

    // Create ul list and append to container
    let ul = document.createElement('ul');
    ul.setAttribute('id', 'ul-results');
    mainContainer.appendChild(ul);

    // Display everything if no input
    if (queryItem == '') {
        for (let i = 0; i < data.Sheet1.length; i++) {
            if (!itemsChosen.includes(data.Sheet1[i].Food_name)) {
                let liItem = document.createElement("li");
                let aElem = document.createElement("a");
                let link = document.createTextNode(data.Sheet1[i].Food_name); 
    
                aElem.setAttribute('onClick', `addItem(${data.Sheet1[i].undefined}, "${data.Sheet1[i].Food_name}"); removeListItem(${data.Sheet1[i].undefined});`);
                aElem.appendChild(link);
    
                liItem.setAttribute('id', data.Sheet1[i].undefined);
                liItem.appendChild(aElem);
    
                ul.appendChild(liItem);
            }
        }
    }

    // Filter items and display the filtered result
    else {
        let foundAName = false;

        // Display all the food names that contain the query name
        for (let i = 0; i < data.Sheet1.length; i++) {
            if (data.Sheet1[i].Food_name.toLowerCase().indexOf(queryItem) > -1 && !itemsChosen.includes(data.Sheet1[i].Food_name)) {
                let liItem = document.createElement("li");
                let aElem = document.createElement("a");
                let link = document.createTextNode(data.Sheet1[i].Food_name); 

                aElem.setAttribute('onClick', `addItem(${data.Sheet1[i].undefined}, "${data.Sheet1[i].Food_name}"); removeListItem(${data.Sheet1[i].undefined})`);
                aElem.appendChild(link);

                liItem.setAttribute('id', data.Sheet1[i].undefined);
                liItem.appendChild(aElem);

                ul.appendChild(liItem);
                foundAName = true;
            }
        }

        // If no results show, display message
        if (foundAName == false) {
            let msg = document.createTextNode("Sorry, we can't find what you're looking for. Instead, add your own item.");
            let liItem = document.createElement("li");
            let aElem = document.createElement("a");

            aElem.setAttribute('style', 'color:white');

            aElem.appendChild(msg);
            liItem.appendChild(aElem);

            ul.appendChild(liItem);
        }
    }
}

// Adds a form entry
function addItem(itemNum, itemName) {
    let resultsArea = document.getElementById('results-container');
    createForm(itemName, resultsArea, itemNum);

    itemsChosen.push(itemName);
}

// Remove the selected food name from the list of foods
function removeListItem(liItem) {
    let liElem = document.getElementById(liItem);
    liElem.parentNode.removeChild(liElem);
}

// Remove a form entry
function removeItem(nameInput, weight_or_calories_input, selector, removeBtn, br1, br2, isFromList) {
    // Remove the name of the food item from the itemsChosen array
    if (isFromList) {
        const index = itemsChosen.indexOf(nameInput.value);
        if (index > -1) {
            itemsChosen.splice(index, 1);
        }
    }

    // Remove nameInput element
    let elem = document.getElementById(nameInput.id);
    elem.remove();

    // Remove timeInput element
    elem = document.getElementById(weight_or_calories_input.id);
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

    if (isFromList) {
        --numOfItemsChosen;    
    }
    else {
        --numOfCustomItems;
    }

    showFoods(event);
} 

// Creates the components of a form entry from the list of foods
function createForm(itemName, resultsArea) {
    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numOfItemsChosen}`);
    labelElem.setAttribute('class', 'form-control mr-3 mb-4 shadow');
    labelElem.setAttribute('id', `item${numOfItemsChosen}nameInput`);
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Food name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;
    labelElem.readOnly = true;

    // Weight of food input
    let inputOZElement = document.createElement("input");
    inputOZElement.setAttribute('type', 'number');
    inputOZElement.setAttribute('name', `item${numOfItemsChosen}weight`);
    inputOZElement.setAttribute('id', `item${numOfItemsChosen}weightInput`);
    inputOZElement.setAttribute('class', 'form-control mr-3 mb-4 shadow');
    inputOZElement.setAttribute('placeholder', 'Weight eaten in oz');
    inputOZElement.setAttribute('step', '0.01');
    inputOZElement.setAttribute('min', '0.01');
    inputOZElement.required = true;

    // Breakfast, Lunch, and Dinner selector
    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `item${numOfItemsChosen}dayeaten`);
    dayEatenElem.setAttribute('id', `item${numOfItemsChosen}selector`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control mr-3 mb-4 shadow');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numOfItemsChosen}Entry`);
    removeEntry.setAttribute('class', 'btn btn-danger mb-4 shadow');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numOfItemsChosen}`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numOfItemsChosen}`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `renameAttributes('item${numOfItemsChosen}nameInput',` + 
                                        `'item${numOfItemsChosen}weightInput',` + 
                                        `'item${numOfItemsChosen}selector',` + 
                                        `'remove${numOfItemsChosen}Entry',` +
                                        `'br1${numOfItemsChosen}',` +
                                        `'br2${numOfItemsChosen}',` +
                                        `true)`);

    inputOZElement.setAttribute('onInput', 'validate_second_field(this)');

    // Appends the entry components to the entry area
    resultsArea.appendChild(labelElem);
    resultsArea.appendChild(inputOZElement);
    resultsArea.appendChild(dayEatenElem);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);

    ++numOfItemsChosen;
}

// Creates the components of a form entry based off of user input
function addCustomItem() {
    let resultsArea = document.getElementById('results-container');

    // Name input, which can be edited
    let foodItemElem = document.createElement("input");
    foodItemElem.setAttribute('type', 'text');
    foodItemElem.setAttribute('name', `item${numOfCustomItems}customName`);
    foodItemElem.setAttribute('class', 'form-control mr-3 mb-4 shadow');
    foodItemElem.setAttribute('id', `item${numOfCustomItems}customName`);
    foodItemElem.setAttribute('placeholder', 'Food name');
    foodItemElem.required = true;

    // Calories of food
    let caloriesElement = document.createElement("input");
    caloriesElement.setAttribute('type', 'number');
    caloriesElement.setAttribute('name', `item${numOfCustomItems}calories`);
    caloriesElement.setAttribute('id', `item${numOfCustomItems}calories`);
    caloriesElement.setAttribute('class','form-control mr-3 mb-4 shadow');
    caloriesElement.setAttribute('placeholder', 'Calories eaten')
    caloriesElement.setAttribute('step', '0.01');
    caloriesElement.setAttribute('min', '0.01');
    caloriesElement.required = true;

    // Breakfast, Lunch, and Dinner selector
    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `item${numOfCustomItems}customSelector`);
    dayEatenElem.setAttribute('id', `item${numOfCustomItems}customSelector`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control mr-3 mb-4 shadow');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numOfCustomItems}customEntry`);
    removeEntry.setAttribute('class', 'btn btn-outline-danger mb-4 shadow');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numOfCustomItems}custom`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numOfCustomItems}custom`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `renameAttributes('item${numOfCustomItems}customName',` + 
                                        `'item${numOfCustomItems}calories',` + 
                                        `'item${numOfCustomItems}customSelector',` + 
                                        `'remove${numOfCustomItems}customEntry',` +
                                        `'br1${numOfCustomItems}custom',` +
                                        `'br2${numOfCustomItems}custom',` +
                                        `false)`);

    caloriesElement.setAttribute('onInput', 'validate_second_field(this)');
    foodItemElem.setAttribute('onInput', 'validate_name_field(this)');

    // Appends the entry components to the entry area
    resultsArea.appendChild(foodItemElem);
    resultsArea.appendChild(caloriesElement);
    resultsArea.appendChild(dayEatenElem);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);
    
    ++numOfCustomItems;
}

// Renames the ids and names of each element
function renameAttributes(nameInput_id, weight_or_calories_id, dayEatenSelector_id, removeBtn_id, br1_id, br2_id, isFromList) {
    let nameInput = document.getElementById(nameInput_id);
    let weight_or_calories_input = document.getElementById(weight_or_calories_id);
    let dayEatenSelector = document.getElementById(dayEatenSelector_id);
    let removeBtn = document.getElementById(removeBtn_id);
    let br1 = document.getElementById(br1_id);
    let br2 = document.getElementById(br2_id);

    // Retrieves the element's index
    let itemNum;
    if (isFromList) {
        itemNum = parseInt(br1_id.replace('br1', ''));
    }
    else {
        itemNum = br1_id.replace('br1', '');
        itemNum = parseInt(itemNum.replace('custom', ''));
    }

    removeItem(nameInput, weight_or_calories_input, dayEatenSelector, removeBtn, br1, br2, isFromList);

    let numOfItems = isFromList ? numOfItemsChosen : numOfCustomItems;

    // Loop through the elements after the current element's index
    for (let i = itemNum + 1; i <= numOfItems; ++i) {

        // Find the elements of the ith index
        nameInput = isFromList ? document.getElementById(`item${i}nameInput`) : document.getElementById(`item${i}customName`);
        weight_or_calories_Input = isFromList ? document.getElementById(`item${i}weightInput`) : document.getElementById(`item${i}calories`);
        dayEatenSelector = isFromList ? document.getElementById(`item${i}selector`) : document.getElementById(`item${i}customSelector`);
        removeBtn = isFromList ? document.getElementById(`remove${i}Entry`) : document.getElementById(`remove${i}customEntry`);
        br1 = isFromList ? document.getElementById(`br1${i}`) : document.getElementById(`br1${i}custom`);
        br2 = isFromList ? document.getElementById(`br2${i}`) : document.getElementById(`br2${i}custom`);

        // Rename ids and names
        if (isFromList) {
            nameInput.setAttribute('id', `item${i - 1}nameInput`);
            nameInput.setAttribute('name', `item${i - 1}`);
            weight_or_calories_Input.setAttribute('id', `item${i - 1}weightInput`);
            weight_or_calories_Input.setAttribute('name', `item${i - 1}weight`);
            dayEatenSelector.setAttribute('id', `item${i - 1}selector`);
            dayEatenSelector.setAttribute('name', `item${i - 1}dayeaten`);
            removeBtn.setAttribute('id', `remove${i - 1}Entry`);
            br1.setAttribute('id', `br1${i - 1}`);
            br2.setAttribute('id', `br2${i - 1}`);
        }
        else {
            nameInput.setAttribute('id', `item${i - 1}customName`);
            nameInput.setAttribute('name', `item${i - 1}customName`);
            weight_or_calories_Input.setAttribute('id', `item${i - 1}calories`);
            weight_or_calories_Input.setAttribute('name', `item${i - 1}calories`);
            dayEatenSelector.setAttribute('id', `item${i - 1}customSelector`);
            dayEatenSelector.setAttribute('name', `item${i - 1}customSelector`);
            removeBtn.setAttribute('id', `remove${i - 1}customEntry`);
            br1.setAttribute('id', `br1${i - 1}custom`);
            br2.setAttribute('id', `br2${i - 1}custom`);
        }

        // Rename the onClick to the appropriate ith index
        if (isFromList) {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}nameInput',` + 
                                              `'item${i - 1}weightInput',` + 
                                              `'item${i - 1}selector',` + 
                                              `'remove${i - 1}Entry',` +
                                              `'br1${i - 1}',` +
                                              `'br2${i - 1}',` +
                                              `true)`);
            weight_or_calories_Input.setAttribute('onInput', 'validate_second_field(this)');
        }
        else {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}customName',` + 
                                              `'item${i - 1}calories',` + 
                                              `'item${i - 1}customSelector',` + 
                                              `'remove${i - 1}customEntry',` +
                                              `'br1${i - 1}custom',` +
                                              `'br2${i - 1}custom',` +
                                              `false)`);
            weight_or_calories_Input.setAttribute('onInput', 'validate_second_field(this)');
            nameInput.setAttribute('onInput', 'validate_name_field(this)');
        }
    }
}