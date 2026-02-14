import relativeTime from 'dayjs/plugin/relativeTime'
import dayjs from "dayjs";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";

const timeFromNow = (dateTime) => {
    dayjs.extend(relativeTime);

    return dayjs().to(dayjs(dateTime));

}

const formatSocialNumber =(count)=>{
    if (count<=0){
        return " ";
    }

    const formatter = new Intl.NumberFormat('en', {
        notation: 'compact',
        compactDisplay: 'short',
        maximumFractionDigits: 1,
    });

   return formatter.format(count)

}

const formatDateTIme = (dateTime) => {
    return dayjs(dateTime).format("MMM D, YYYY HH:mA")
}

const BASE_URLA = "https://app.chefpilot.live";
const BASE_URL = "http://localhost:8000";

const PHOTO_PLACEHOLDER = "https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.png";

const account = await CapacitorPersistentAccount.readAccount()

const AUTH_HEADERS = account.data ? {headers: {Authorization: "Bearer " + account.data.token}} : {headers: {Authorization: "Bearer 7|FsuRNUL37h3oD7SWfwuWHmuNUuSq47bfO2Gd6dRza84df063"}};



export {
    timeFromNow,
    formatDateTIme,
    formatSocialNumber,
    BASE_URL,
    PHOTO_PLACEHOLDER,
    AUTH_HEADERS
}