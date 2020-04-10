let numOfItemsChosen = 0;
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

            liItem.setAttribute('class', 'noResults');

            aElem.appendChild(msg);
            liItem.appendChild(aElem);

            ul.appendChild(liItem);
        }
    }
}

// Adds a form entry
function addItem(itemNum, itemName) {
    let resultsArea = document.getElementById('eatenForm');
    createForm(itemName, resultsArea, itemNum);

    itemsChosen.push(itemName);
}

// Remove the selected exercise name from the list of exercises
function removeListItem(liItem) {
    let liElem = document.getElementById(liItem);
    liElem.parentNode.removeChild(liElem);
}

// Remove a form entry
function removeItem(nameInput, weightInput, selector, removeBtn, br1, br2, isFromList) {
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

    showFoods(event);
} 

// Creates the components of a form entry from the list of foods
function createForm(itemName, resultsArea, itemNum) {
    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numOfItemsChosen}`);
    labelElem.setAttribute('class', 'form-control mr-3');
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
    inputOZElement.setAttribute('class', 'form-control mr-3');
    inputOZElement.setAttribute('placeholder', 'Weight eaten in oz');
    inputOZElement.setAttribute('step', '0.01');
    inputOZElement.setAttribute('min', '0');
    inputOZElement.required = true;

    // Breakfast, Lunch, and Dinner selector
    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `item${numOfItemsChosen}dayeaten`);
    dayEatenElem.setAttribute('id', `item${numOfItemsChosen}selector`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control mr-3');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numOfItemsChosen}Entry`);
    removeEntry.setAttribute('class', 'btn btn-light');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

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
                                        `br2${numOfItemsChosen},` +
                                        `true)`);

    // Appends the entry components to the entry area
    resultsArea.appendChild(labelElem);
    resultsArea.appendChild(inputOZElement);
    resultsArea.appendChild(dayEatenElem);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);

    numOfItemsChosen++;
}

// Creates the components of a form entry based off of user input
function addCustomItem() {
    let resultsArea = document.getElementById('eatenForm');

    // Name input, which can be edited
    let foodItemElem = document.createElement("input");
    foodItemElem.setAttribute('type', 'text');
    foodItemElem.setAttribute('name', `item${numOfItemsChosen}`);
    foodItemElem.setAttribute('class', 'form-control mr-3');
    foodItemElem.setAttribute('id', `item${numOfItemsChosen}nameInput`);
    foodItemElem.setAttribute('placeholder', 'Food name');
    foodItemElem.required = true;

    // Weight of food input
    let inputOZElement = document.createElement("input");
    inputOZElement.setAttribute('type', 'number');
    inputOZElement.setAttribute('name', `item${numOfItemsChosen}weight`);
    inputOZElement.setAttribute('id', `item${numOfItemsChosen}weightInput`);
    inputOZElement.setAttribute('class','form-control mr-3');
    inputOZElement.setAttribute('placeholder', 'Weight eaten in oz')
    inputOZElement.setAttribute('step', '0.01');
    inputOZElement.setAttribute('min', '0');
    inputOZElement.required = true;

    // Breakfast, Lunch, and Dinner selector
    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `item${numOfItemsChosen}dayeaten`);
    dayEatenElem.setAttribute('id', `item${numOfItemsChosen}selector`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control mr-3');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numOfItemsChosen}Entry`);
    removeEntry.setAttribute('class', 'btn btn-light');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

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
                                        `br2${numOfItemsChosen},` +
                                        `false)`);

    // Appends the entry components to the entry area
    resultsArea.appendChild(foodItemElem);
    resultsArea.appendChild(inputOZElement);
    resultsArea.appendChild(dayEatenElem);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);
    
    numOfItemsChosen++;
}