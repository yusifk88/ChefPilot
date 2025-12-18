import relativeTime from 'dayjs/plugin/relativeTime'
import dayjs from "dayjs";

const timeFromNow = (dateTime) => {
    dayjs.extend(relativeTime);

    return dayjs().to(dayjs(dateTime));
}


export {
    timeFromNow
}