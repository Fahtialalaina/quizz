log("Bonjours tous les mondes !")

function log($mes){
    console.log($mes)
}

// $('#families_to_questions_title').select2({
//     allowClear: true,
// });
$('#myTable').DataTable();


var $post_families = $('#families_to_questions_title');
//$hide = $('#question-title, #question-texteComplementaire, #question-autreTexte, #question-types, #question-images');
$hide = $('.question_questions, .questions_answers');
$hide.hide();
$('#question_submit').attr('disabled', true);
 //GET SERVICE INTO DIRECTION
//GET SERVICE INTO DIRECTION
$post_families.change(function () {
    var url = 'getCompetence/' + $post_families.val();
    $.getJSON(url, function (data) {
        var items = '';
        $('#question_competences').empty();
        if (data == "") {
            console.log("data is null");
            $hide.hide();
            $('#question_submit').attr('disabled', true);
            items += '<option value="'+0+'">Aucune Competence dans cette Articles </option>';
        }
        if (data != "") {
            console.log("data is " + data);
            $.each(data, function (i, competence) {
                items += '<option value="' + competence.id + '"> ' + competence.title + ' </option>';
            });
            $hide.show();
            $('#question_submit').attr('disabled', false);
        }

        $('#question_competences').html(items);
    });

});




// $('#add_answers').on('click', function(){
//     $('#question_answers_title').siblings('span.error').css('visibility', 'hidden');
//     //Validation du champ si Vide
//     var isAllValid = true;

//     if (!($('#question_answers_title').val().trim() != '')) {
//         isAllValid = false;
//         $('#question_answers_title').siblings('span.error').css('visibility', 'visible');
//     }else{
//         isAllValid = true;
//         $('#question_answers_title').siblings('span.error').css('visibility', 'hidden');
//     }

//     if (isAllValid) {
//         console.log("Salut answer !");
//         var $newRow = $('#mainrow').clone().removeAttr('id');

//         //Replace add button with remove button
//         $('#add_answers', $newRow).addClass('remove').val('Supprimer').removeClass('btn-success').addClass('btn-danger');

//         //remove id attribute from new clone row
//         $('#question_answers_title, #question_answers_isAnswer, #add', $newRow).removeAttr('id');
//         $('span.error', $newRow).remove();

//         //append clone row
//         $('#orderdetailsItems').append($newRow);

//         //clear select data
//         $('#question_answers_title').val('');
//         $('#question_answers_isAnswer').val('0')
//         $('#orderItemError').empty();
//     }
// });

// //remove button click event
// $('#orderdetailsItems').on('click', '.remove', function () {
//     $(this).parents('tr').remove();
// });


// $('#add_answers').on('click', function(){
//     $('#teste_titleAns').siblings('span.error').css('visibility', 'hidden');
//     //Validation du champ si Vide
//     var isAllValid = true;

//     if (!($('#teste_titleAns').val().trim() != '')) {
//         isAllValid = false;
//         $('#teste_titleAns').siblings('span.error').css('visibility', 'visible');
//     }else{
//         isAllValid = true;
//         $('#teste_titleAns').siblings('span.error').css('visibility', 'hidden');
//     }

//     if (isAllValid) {
//         console.log("Salut answer !");
//         var $newRow = $('#mainrow').clone().removeAttr('id');

//         //Replace add button with remove button
//         $('#add_answers', $newRow).addClass('remove').val('Supprimer').removeClass('btn-success').addClass('btn-danger');

//         //remove id attribute from new clone row
//         $('#answers_title, #answers_isAnswer, #add', $newRow).removeAttr('id');
//         $('span.error', $newRow).remove();

//         //append clone row
//         $('#orderdetailsItems').append($newRow);

//         //clear select data
//         $('#answers_title').val('');
//         $('#answers_isAnswer').val('0')
//         $('#orderItemError').empty();
//     }
// });

// //remove button click event
// $('#orderdetailsItems').on('click', '.remove', function () {
//     $(this).parents('tr').remove();
// });