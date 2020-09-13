const ret = {
    data: '',
    status_code: '',
    error: '',
}
//TODO: дописать отправку сообщения об ошибке на почту 
export async function ajaxAction( url : string, method : string, data? : FormData | any ) {
    try {
        var d
        var headers
        if( data instanceof FormData ){
            d = data
        }
        else if ( data ) {
            d = JSON.stringify({ data }),
            headers ={ 'Content-Type': 'application/json' }
        }
        
        await fetch('http://localhost/komus_new/api/' + url, {
            method: method,
            body: d,
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: headers,
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
        })
            .then(( response ) => {
                ret.status_code = response.status.toString()
                return response.json()        
            })
            .then(( data ) => {
            ret.data = data
            })
    } catch ( err ) {
        ret.error = err
        console.log( err )
    }
    return ret
}
