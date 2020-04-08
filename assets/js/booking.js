$(document).ready(function(){
    console.log('Bootstrap-datePicker script started ')
    console.log('notAvailableDays:'+ notAvailableDays)
    $('#booking_startDate, #booking_endDate').datepicker({
        format : 'dd/mm/yyyy',
        autoclose: true,
        datesDisabled: notAvailableDays
    });
    console.log("oneNightPrice: ", oneNightPrice);
    $('#booking_startDate, #booking_endDate').on('change', calculateAmount);
})


function calculateAmount(){
    //On chope les dates dans les champs et on les met au format admissible par javascript
    // 18/09/2018
    // (\d+)\/(\d+)\/(\d{4})
    // 2018-09-18
    let endDate =  new Date($('#booking_endDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/,'$3-$2-$1'));
    let startDate =  new Date($('#booking_startDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/,'$3-$2-$1'));

    if(startDate && endDate && startDate < endDate){
        const DAY_TIME = 24 * 60 * 60 * 1000;
        let interval =  endDate.getTime() - startDate.getTime();
        let days = interval / DAY_TIME;
        let amount = oneNightPrice * days;

        $('#days').text(days);
        $('#amount').text(amount.toLocaleString('fr-FR'));
    }
}