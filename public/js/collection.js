function handleDelete(){
    $(event.target).parent().parent().remove()
    if ($(".btn-group").children("a").length == 0 && $(".regs").children(".card").length === 0) {
        $(".btn-group").append("<a id='add' class='addForm btn btn-primary' href='#' onclick='handleAdd()'>Generer un formulaire</a>")
    }
}

function handleAdd(){
    
    
    
    let $prototype = $(".regs").data("prototype")
    let $collectionHolder = $(".regs")

    $collectionHolder.data("index", $collectionHolder.children(".card").length)
    let index = $collectionHolder.data('index');
        
    $newForm = $prototype.replace(/__name__/g, index);
        
    let btnAdd = "<a class='addForm btn btn-primary' href='#' onclick='handleAdd()'>Ajout</a>"
    let btnDelete = "<a class='deleteForm btn btn-danger' href='#' onclick='handleDelete()'>Delete</a>"
            
        
    $collectionHolder.append(
        "<div class='card bg-light mb-3'>"+
            "<div class='card-header'>"+
                btnAdd+btnDelete+
            "</div>"+
            "<div class='card-body'>"+
                $newForm+
            "</div>"+
        "</div>"
    )

    // // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);
    index = index + 1 

    if ($(".regs").children(".card").length != 0) {
        $("#add").remove()
    }
}