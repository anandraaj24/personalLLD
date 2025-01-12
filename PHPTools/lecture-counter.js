var courseItemsDiv = document.querySelector('.courseItems');
var allCoursesList = courseItemsDiv.querySelectorAll('.courseItem.courseItem__id');
var allCourseDetails = [];
allCoursesList.forEach(item => {
    var courseDetails = {
        courseId: item.getAttribute("data-id"),
        courseTitle: item.getAttribute("data-title")
    }
    allCourseDetails.push(courseDetails);
    
})

allCourseDetails.forEach(item => {
    var courseDiv = document.querySelector('div[data-id="'+item.courseId+'"]');
    var courseTitle = item.courseTitle;
    var lecturesDiv = courseDiv.querySelectorAll('.courseSubItem.courseItem__id[data-type="video"]').length+1;
    console.log("Subject Name: " + courseTitle + ", Total Lectures: " + lecturesDiv);
})



// ***********Original************


var courseItemsDiv = document.querySelector('.courseItems');
var allCoursesList = courseItemsDiv.querySelectorAll('.courseItem.courseItem__id');
var allCourseDetails = [];
allCoursesList.forEach(item => {
    var courseDetails = {
        courseId: item.getAttribute("data-id"),
        courseTitle: item.getAttribute("data-title")
    }
    allCourseDetails.push(courseDetails);
    
})

allCourseDetails.forEach(item => {
    var courseDiv = document.querySelector('div[data-id="'+item.courseId+'"]');
    var courseTitle = item.courseTitle;
    var lecturesDiv = courseDiv.querySelectorAll('.courseSubItem.courseItem__id[data-type="video"]');
    console.log("Subject Name: " + courseTitle + ", Total Lectures: " + lecturesDiv.length+1);
})

