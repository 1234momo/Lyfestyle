let numOfSelectedItems = 0;
let numOfCustomItems = 0;
let itemsChosen = [];

function showExercises(e) {
    // Prevent page from reloading
    e.preventDefault();

    // Call json file with exercises and then display the data
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

// Display all the exercises in the json file
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

        // Display all the exercise names that contain the query name
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

            liItem.setAttribute('style', 'color:white');

            aElem.appendChild(msg);
            liItem.appendChild(aElem);

            ul.appendChild(liItem);
        }
    }
}

// Adds a form entry
function addAFormItem(itemNum, itemName) {
    let resultsArea = document.getElementById('results-container');
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
    // Remove the name of the exercise from the itemsChosen array
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

    if (isFromList) {
        --numOfSelectedItems;
    }
    else {
        --numOfCustomItems;
    }

    showExercises(event);
} 

// Creates the components of a form entry from the list of exercises
function createForm(itemName, resultsArea) {
    // Name input, but disabled because the name is from json file
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numOfSelectedItems}`);
    labelElem.setAttribute('class', 'form-control mr-5 mb-4 shadow');
    labelElem.setAttribute('id', `item${numOfSelectedItems}`);
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('placeholder', 'Exercise name');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;
    labelElem.readOnly = true;

    // Time input
    let inputTimeElement = document.createElement("input");
    inputTimeElement.setAttribute('type', 'number');
    inputTimeElement.setAttribute('name', `item${numOfSelectedItems}timeInput`);
    inputTimeElement.setAttribute('id', `item${numOfSelectedItems}timeInput`);
    inputTimeElement.setAttribute('class', 'form-control mr-5 mb-4 shadow');
    inputTimeElement.setAttribute('placeholder', 'Minutes exercised');
    inputTimeElement.setAttribute('step', '1');
    inputTimeElement.setAttribute('min', '1');
    inputTimeElement.required = true;

    // Remove (X) button
    let removeEntry = document.createElement("button");
    removeEntry.setAttribute('id', `remove${numOfSelectedItems}Entry`);
    removeEntry.setAttribute('class', 'btn btn-danger mb-4 shadow');
    removeEntry.setAttribute('type', 'button');
    removeEntry.innerHTML = "X";

    // 2 line breakers for space consistency between entries
    let linebreakElem = document.createElement("br");
    linebreakElem.setAttribute('id', `br1${numOfSelectedItems}`);
    let linebreakElem2 = document.createElement("br");
    linebreakElem2.setAttribute('id', `br2${numOfSelectedItems}`);

    // Create onclick listner to know when to remove an entry
    removeEntry.setAttribute('onClick', `renameAttributes('item${numOfSelectedItems}',` + 
                                        `'item${numOfSelectedItems}timeInput',` + 
                                        `'remove${numOfSelectedItems}Entry',` +
                                        `'br1${numOfSelectedItems}',` +
                                        `'br2${numOfSelectedItems}',` +
                                        `true)`);

    inputTimeElement.setAttribute('onInput', 'validate_time_field(this)');
    
    // Appends the entry components to the entry area
    resultsArea.appendChild(labelElem);
    resultsArea.appendChild(inputTimeElement);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);

    ++numOfSelectedItems;
}

// Creates the components of a form entry based off of user input
function addCustomItem() {
    let resultsArea = document.getElementById('results-container');

    // Name input, which can be edited
    let exerciseNameInput = document.createElement("input");
    exerciseNameInput.setAttribute('type', 'text');
    exerciseNameInput.setAttribute('name', `item${numOfCustomItems}customName`);
    exerciseNameInput.setAttribute('id', `item${numOfCustomItems}customName`);
    exerciseNameInput.setAttribute('class', 'form-control mr-5 mb-4 shadow');
    exerciseNameInput.setAttribute('placeholder', 'Exercise name');
    exerciseNameInput.required = true;

    // Calorie input
    let caloriesInput = document.createElement("input");
    caloriesInput.setAttribute('type', 'number');
    caloriesInput.setAttribute('name', `item${numOfCustomItems}calories`);
    caloriesInput.setAttribute('id', `item${numOfCustomItems}calories`);
    caloriesInput.setAttribute('class', 'form-control mr-5 mb-4 shadow');
    caloriesInput.setAttribute('placeholder', 'Calories burned');
    caloriesInput.setAttribute('step', '0.01');
    caloriesInput.setAttribute('min', '0.01');
    caloriesInput.required = true;

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
                                        `'remove${numOfCustomItems}customEntry',` +
                                        `'br1${numOfCustomItems}custom',` +
                                        `'br2${numOfCustomItems}custom',` +
                                        `false)`);

    caloriesInput.setAttribute('onInput', 'validate_second_field(this)');
    
    // Appends the entry components to the entry area
    resultsArea.appendChild(exerciseNameInput);
    resultsArea.appendChild(caloriesInput);
    resultsArea.appendChild(removeEntry);
    resultsArea.appendChild(linebreakElem);
    resultsArea.appendChild(linebreakElem2);
    
    ++numOfCustomItems;
}

// Renames the ids and names of each element
function renameAttributes(nameInput_id, second_input_id, removeBtn_id, br1_id, br2_id, isFromList) {
    let nameInput = document.getElementById(nameInput_id);
    let second_input = document.getElementById(second_input_id);
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

    removeItem(nameInput, second_input, removeBtn, br1, br2, isFromList);

    let numOfItems = isFromList ? numOfSelectedItems : numOfCustomItems;

    // Loop through the elements after the current element's index
    for (let i = itemNum + 1; i <= numOfItems; ++i) {

        // Find the elements of the ith index
        nameInput = isFromList ? document.getElementById(`item${i}`) : document.getElementById(`item${i}customName`);
        second_input = isFromList ? document.getElementById(`item${i}timeInput`) : document.getElementById(`item${i}calories`);
        removeBtn = isFromList ? document.getElementById(`remove${i}Entry`) : document.getElementById(`remove${i}customEntry`);
        br1 = isFromList ? document.getElementById(`br1${i}`) : document.getElementById(`br1${i}custom`);
        br2 = isFromList ? document.getElementById(`br2${i}`) : document.getElementById(`br2${i}custom`);

        // Rename ids and names
        if (isFromList) {
            nameInput.setAttribute('id', `item${i - 1}`);
            nameInput.setAttribute('name', `item${i - 1}`);
            second_input.setAttribute('id', `item${i - 1}timeInput`);
            second_input.setAttribute('name', `item${i - 1}timeInput`);
            removeBtn.setAttribute('id', `remove${i - 1}Entry`);
            br1.setAttribute('id', `br1${i - 1}`);
            br2.setAttribute('id', `br2${i - 1}`);
        }
        else {
            nameInput.setAttribute('id', `item${i - 1}customName`);
            nameInput.setAttribute('name', `item${i - 1}customName`);
            second_input.setAttribute('id', `item${i - 1}calories`);
            second_input.setAttribute('name', `item${i - 1}calories`);
            removeBtn.setAttribute('id', `remove${i - 1}customEntry`);
            br1.setAttribute('id', `br1${i - 1}custom`);
            br2.setAttribute('id', `br2${i - 1}custom`);
        }

        // Rename the onClick to the appropriate ith index
        if (isFromList) {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}',` + 
                                              `'item${i - 1}timeInput',` + 
                                              `'remove${i - 1}Entry',` +
                                              `'br1${i - 1}',` +
                                              `'br2${i - 1}',` +
                                              `true)`);

            second_input.setAttribute('onInput', 'validate_time_field(this)');
        }
        else {
            removeBtn.setAttribute('onClick', `renameAttributes('item${i - 1}customName',` + 
                                              `'item${i - 1}calories',` + 
                                              `'remove${i - 1}customEntry',` +
                                              `'br1${i - 1}custom',` +
                                              `'br2${i - 1}custom',` +
                                              `false)`);

            second_input.setAttribute('onInput', 'validate_second_field(this)');
        }
    }
}