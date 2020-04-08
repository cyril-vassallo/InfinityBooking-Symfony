    $('#add-image').click(function(){
        //Je récupère le numéro du futur champ que je vais créer
        const index = +$('#widgets-counter').val();
        console.log(index);

    
        //Je récupère le prototype des entrées
        const template = $('#annonce_images').data('prototype').replace(/__name__/g,index);


        // J'inject ce code au sein de la div
        $('#annonce_images').append(template);

        //J'incrémente le widget compteur
        $('#widgets-counter').val(index + 1);

        //Je gere le bouton supprimer
        handleDeleteButtons();
    });

    function handleDeleteButtons(){
        $('button[data-action = "delete"]').click(function(){
            const target = this.dataset.target;
            console.log(target);
            $(target).remove();
        });
    }

    function updateCounterImages(){
        const count = +$("#annonce_images div.form-group").length;
        console.log(count);
        $("#widgets-counter").val(count);   
    }

updateCounterImages();
handleDeleteButtons();