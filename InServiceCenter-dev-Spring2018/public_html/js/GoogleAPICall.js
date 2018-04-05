var Client_Id = "926767991162-l7nms773dl30u94mk72luumt6c92o07f.apps.googleusercontent.com";
var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
var SCOPES = "https://www.googleapis.com/auth/calendar";
var login_vari = document.getElementById("sign-in");
var logout_vari = document.getElementById("log-out");
var private_CalID ="9l70eg2m80ab0f8o19shd3nt70@group.calendar.google.com";
//Start of Authentication====================================================
function sign_in()
{
    gapi.auth2.getAuthInstance().signIn();
}
function handleClientLoad() {
    gapi.load('client:auth2', initClient);
}
function initClient() {
    gapi.client.init({
        discoveryDocs: DISCOVERY_DOCS,
        clientId: Client_Id,
        scope: SCOPES
    }).then(function () {
        // Listen for sign-in state changes.
        gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

        // Handle the initial sign-in state.
        updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
    });
}
function updateSigninStatus(isSignedIn) {
    // When signin status changes, this function is called.
    // If the signin status is changed to signedIn, we make an API call.
    if (isSignedIn) {
        ShowForm();
    }
}

function handleSignInClick(event) {
    // Ideally the button should only show up after gapi.client.init finishes, so that this
    // handler won't be called before OAuth is initialized.
    gapi.auth2.getAuthInstance().signIn();
}

function handleSignOutClick(event) {
    gapi.auth2.getAuthInstance().signOut();
    document.getElementById("admin-content").style.display="none";
    login_vari.style.display="block";
    logout_vari.style.display="none";
}

function ShowForm()
{
    document.getElementById("admin-content").style.display = "block";
    login_vari.style.display = "none";
    logout_vari.style.display = "block";
}
//End of Authentication===================================================
$('#get-list-btn').click(function(){
    var Calendar = getID($('#calendar-list').val());
    var eventList = $('#content-up-del');
    //Clear any Lists that were on the page
    eventList.empty();
    gapi.client.calendar.events.list({
        'calendarId': Calendar,
        'timeMin': (new Date()).toISOString(),
        'showDeleted': false,
        'singleEvents': true,
        'maxResults': 40,
        'orderBy': 'startTime'
    }).then(function(response) {
        var events = response.result.items;

        if (events.length > 0) {
         for (i = 0; i < events.length; i++) {
                var event = events[i];
              var when = event.start.dateTime;

                if (!when) {
                    when = event.start.date;
             }
             eventList.append("<li class='del-up-item list-group-item'> " + event.summary + " Date: " + when +
                 " <button data-id='" + event.id + "' class='delete btn-danger'>Delete</button></li>");
         }
        }
        else {
        eventList.append('<li> No Events </li>');
        }
        eventList.delegate('.delete', 'click', function () {
            gapi.client.calendar.events.delete({
                'calendarId': Calendar,
                'eventId': $(this).attr('data-id')
            }).then(function(){
                $(this).closest('li').remove();
            })
        });

    });
});

$(document).ready(function() {
    $("#create-event").click(function () {
        var room = $("#in_room").val();
        var title=$("#private-title").val();
        var description=$("#description").val();
        var date = $("#start-date").val();
        var dateArray = date.split('/');
        var startAMPM = $('#start-AM-PM').val();
        var startHour = $('#start-hour').val();
        if(startHour.length ===1) startHour = "0" + startHour;
        var startMinute = $('#start-minute').val();
        //check if either AM or PM then add if PM
        startHour = checkAM_PM(startAMPM, startHour);
        var startTime = 'T' + startHour + ':' + startMinute + ':00-05:00';

        var endAMPM = $('#end-AM-PM').val();
        var endHour = $('#end-hour').val();
        if(endHour.length ===1) endHour = "0" + endHour;
        var end_12=endHour;
        var endMinute = $('#end-minute').val();
        endHour = checkAM_PM(endAMPM, endHour);
        var endTime = 'T' + endHour + ':' + endMinute + ':00-05:00';

        var event = {
            'summary': room + ': busy',
            'location': 'Athens, AL',
            'description': room + ' is reserved until ' + end_12 +":"+ endMinute + endAMPM,
            'start': {
                'dateTime': dateArray[2] + '-' + dateArray[0] + '-' + dateArray[1] + startTime

            },
            'end': {
                'dateTime': dateArray[2] + '-' + dateArray[0] + '-' + dateArray[1] + endTime
            }
        };

        var CalendarID = getID(room);
        var request = gapi.client.calendar.events.insert({
            'calendarId': CalendarID,
            'resource': event
        });
        request.execute(function(event) {
            console.log("Public Success");
        });

        var private_event={
            'summary': room +" " + title,
            'location': 'Athens, AL',
            'description': description,
            'start': {
                'dateTime': dateArray[2] + '-' + dateArray[0] + '-' + dateArray[1] + startTime

            },
             'end': {
                 'dateTime': dateArray[2] + '-' + dateArray[0] + '-' + dateArray[1] + endTime
            }
        };
         var private_request = gapi.client.calendar.events.insert({
            'calendarId': private_CalID,
            'resource': private_event
        });
        private_request.execute(function(event) {
            console.log("Private Success");
        });
    });

});
function checkDates(d, m, y)
{
    var day, month;
    day = parseFloat(d, 10);
    month = parseFloat(m, 10);
    if(day<1 || day>12 || isNaN(day)) return false;
    else if(month<1 || month>12 || isNaN(month)) return false;
    else if(y.length !== 4)return false;
    else return true;
}
function checkTime(h, m)
{
    var hour, minute;
    hour = parseFloat(h, 10);
    minute = parseFloat(m, 10);
    if(!isNaN(hour) ||  hour >12 || hour < 1) return false;
    else if(!isNaN(minute) || minute < 0 || minute > 59) return false;
    else return true;
}

function checkAM_PM(am_pm, hour)
{
    var fHour = parseFloat(hour, 10);
    if(am_pm ==='AM' && fHour === 12)
    {
       fHour -=12;
    }
    else if(am_pm === 'PM' && fHour < 12)
    {
        fHour+=12;
    }
    else return fHour.toString();

    return fHour.toString();

}
function getID(room)
{
    var CalendarID;
    if (room === 'Room A') {
        CalendarID = 'at8l5tsi7g34k43564d22g4ddc@group.calendar.google.com';
    }
    else if (room === 'Room B') {
        CalendarID = 'redd09jcf350kg52s8kntu91hc@group.calendar.google.com';
    }
    else if (room === 'Room C') {
        CalendarID = '3r7v3qeevnub3rm0ilmnd0ndq4@group.calendar.google.com';
    }
    else if (room === 'Conference') {
        CalendarID = 'lfrdt41o0cb63fge3d0m4f19ag@group.calendar.google.com';
    }
    return CalendarID;
}
