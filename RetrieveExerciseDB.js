let numOfEntries = 0;
let itemsChosen = [];

function showExercises(e) {
    // Prevent page from reloading
    e.preventDefault();

    // Call json file with foods and then display the data
    fetch("../database/exercise_database.json")
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
            if (!itemsChosen.includes(data.Sheet1[i].Exercise_name)) {
                let liItem = document.createElement("li");
                let aElem = document.createElement("a");
                let link = document.createTextNode(data.Sheet1[i].Exercise_name); 
    
                aElem.setAttribute('onClick', `addAFormItem(${data.Sheet1[i].undefined}, "${data.Sheet1[i].Exercise_name}"); removeListItem(${data.Sheet1[i].undefined});`);
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

            // If a exercise name has part of the query, display it
            if (data.Sheet1[i].Exercise_name.toLowerCase().indexOf(queryItem) > -1 && !itemsChosen.includes(data.Sheet1[i].Exercise_name)) {
                let liItem = document.createElement("li");
                let aElem = document.createElement("a");
                let link = document.createTextNode(data.Sheet1[i].Exercise_name); 

                aElem.setAttribute('onClick', `addAFormItem(${data.Sheet1[i].undefined}, "${data.Sheet1[i].Exercise_name}"); removeListItem(${data.Sheet1[i].undefined})`);
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
function addAFormItem(itemNum, itemName) {
    let resultsArea = document.getElementById('exerciseForm');
    createForm(itemName, resultsArea, itemNum);

    itemsChosen.push(itemName);
}

// Remove the selected exercise name from the list of exercises
function removeListItem(liItem) {
    let liElem = document.getElementById(liItem);
    liElem.parentNode.removeChild(liElem);
}

// Remove a form entry
function removeItem(nameInput, timeInput, removeBtn, br1, br2, isFromList) {
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

    showExercises(event);
} 

// Creates the components of a form entry from the list of exercises
function createForm(itemName, resultsArea, itemNum) {
    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numOfEntries}`);
    labelElem.setAttribute('class', 'form-control mr-4');
    labelElem.setAttribute('id', `item${numOfEntries}nameInput`);
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Exercise name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;
    labelElem.readOnly = true;

    // Time input
    let inputTimeElement = document.createElement("input");
    inputTimeElement.setAttribute('type', 'number');
    inputTimeElement.setAttribute('name', `item${numOfEntries}time`);
    inputTimeElement.setAttribute('id', `item${numOfEntries}timeInput`);
    inputTimeElement.setAttribute('class', 'form-control mr-4');
    inputTimeElement.setAttribute('placeholder', 'Minutes exercised');
    inputTimeElement.setAttribute('step', '1');
    inputTimeElement.setAttribute('min', '1');
    inputTimeElement.required = true;

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numOfEntries}Entry`);
    removeEntry.setAttribute('class', 'btn btn-light');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numOfEntries}`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numOfEntries}`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `removeItem(item${numOfEntries}nameInput,` + 
                                        `item${numOfEntries}timeInput,` + 
                                        `remove${numOfEntries}Entry,` +
                                        `br1${numOfEntries},` +
                                        `br2${numOfEntries},` +
                                        `true)`);
    
    // Appends the entry components to the entry area
    resultsArea.appendChild(labelElem);
    resultsArea.appendChild(inputTimeElement);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);

    numOfEntries++;
}

// Creates the components of a form entry based off of user input
function addCustomItem() {
    let resultsArea = document.getElementById('exerciseForm');

    // Name input, which can be edited
    let foodItemElem = document.createElement("input");
    foodItemElem.setAttribute('type', 'text');
    foodItemElem.setAttribute('name', `item${numOfEntries}`);
    foodItemElem.setAttribute('class', 'form-control mr-4');
    foodItemElem.setAttribute('id', `item${numOfEntries}nameInput`);
    foodItemElem.setAttribute('placeholder', 'Exercise name');
    foodItemElem.required = true;

    // Time input
    let inputTimeElement = document.createElement("input");
    inputTimeElement.setAttribute('type', 'number');
    inputTimeElement.setAttribute('name', `item${numOfEntries}time`);
    inputTimeElement.setAttribute('id', `item${numOfEntries}timeInput`);
    inputTimeElement.setAttribute('class', 'form-control mr-4');
    inputTimeElement.setAttribute('placeholder', 'Minutes exercised');
    inputTimeElement.setAttribute('step', '1');
    inputTimeElement.setAttribute('min', '1');
    inputTimeElement.required = true;

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numOfEntries}Entry`);
    removeEntry.setAttribute('class', 'btn btn-light');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";
    
    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numOfEntries}`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numOfEntries}`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `removeItem(item${numOfEntries}nameInput,` + 
                                        `item${numOfEntries}timeInput,` + 
                                        `remove${numOfEntries}Entry,` +
                                        `br1${numOfEntries},` +
                                        `br2${numOfEntries},` +
                                        `false)`);
    
    // Appends the entry components to the entry area
    resultsArea.appendChild(foodItemElem);
    resultsArea.appendChild(inputTimeElement);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);
    
    numOfEntries++;
}