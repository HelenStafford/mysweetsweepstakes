function ajax_call(a,t,l="GET"){
    return $.ajax(
        {
            type:l,
            url:a,
            data:t
        }
    )
}

