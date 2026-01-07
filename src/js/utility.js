import relativeTime from 'dayjs/plugin/relativeTime'
import dayjs from "dayjs";

const timeFromNow = (dateTime) => {
    dayjs.extend(relativeTime);

    return dayjs().to(dayjs(dateTime));
}

const formatDateTIme = (dateTime)=>{
    return dayjs(dateTime).format("MMM D, YYYY HH:mA")
}

const BASE_URL = "https://cpapi.flobaze.com";
const BASE_URLA = "http://localhost:8000";




export {
    timeFromNow,
    formatDateTIme,
    BASE_URL
}