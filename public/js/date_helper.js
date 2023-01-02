function DateChange(bsSelector, adSelector)
{
    FormatBs(bsSelector);
    $(adSelector).val(BS2AD($(bsSelector).val()));
};

function FormatBs(bsSelector) {
    var date = $(bsSelector).val();
    var year = null;
    var month = null;
    var day = null;
    var res = null;
    var msg = null;
    var result = false;
    if (date == null || date == "")
        return;
    if (date.search("/") > 1) {
        res = date.split("/");
    } else if (date.search("-") > 1) {
        res = date.split("-");
    } else {
        switch (date.length) {
            case (8):
                day = date.substr(6, 2);
                month = date.substr(4, 2);
                year = date.substr(0, 4);
                break;
            case (7):
                day = date.substr(5, 2);
                month = date.substr(3, 2);
                year = parseInt(date.substr(0, 3)) + 2000;
                break;
            case (6):
                day = date.substr(4, 2);
                month = date.substr(2, 2);
                year = parseInt(date.substr(0, 2)) + 2000;
                break;
            case (4):
                day = date.substr(2, 2);
                month = date.substr(0, 2);
                year = 2000;
                break;
            case (0):
                break;
            default:
              
                msg = "Invalid Date.";
                break;
        }
    }
    if (res != null) {
        if (res.length == 3) {
            year = res[0];
            month = res[1];
            day = res[2];
            if (year.length <= 3) {
                year = parseInt(res[0]) + 2000;
            }
            if (month.length == 1) {
                month = "0" + res[1];
            }
            if (day.length == 1) {
                day = "0" + res[2];
            }
        } else {

            msg = "Invalid Date.";
        }
    }
    if (month > 12 || day > 32) {
        msg = "Invalid Date";
        $(bsSelector).focus();
    }
    if (msg == null) {
        var fdate = year + "-" + month + "-" + day;
        $(bsSelector).val(fdate);
        result = true;
    } else {
        alert(msg);
        $(bsSelector).val("").change();
        $(bsSelector).focus();
        result = false;
    }

    return result;
};

  


