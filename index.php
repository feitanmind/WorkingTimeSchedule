<script>
        
        let lang = document.cookie;
        
        if(lang.search("lang") == -1){
            const userLocale = navigator.languages && navigator.languages.lengh ? navigator.languages[0] : navigator.language;
            document.cookie = "lang="+userLocale+";expires=Fri, 31 Dec 9999 23:59:59 GMT";
            console.log('Create new'+userLocale);
            if(lang.substr(lang.search("lang")+5,2) == 'en'){
                location.href = "./login/en-US";
            }else{
                location.href = "./login/pl-PL";
            }
        }else{
            if(lang.substr(lang.search("lang")+5,2) == 'en'){
                location.href = "./login/en-US";
            }else{
                location.href = "./login/pl-PL";
            }
        }

</script>