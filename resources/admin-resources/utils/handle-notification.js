import {useNotificationStore} from "../components/shared/notification/notificationStore.js";
import formatValidationErrors from "./format-validation-errors.js";

const notifyStore = useNotificationStore();

export const handleErrors = (error, validationError= {}) => {
    const errorMessage = error.response?.data?.message || "Something went wrong.";
    let notifyObj = {message: errorMessage, type: "error"}

    if ([419, 401, 403].includes(error.response.status)) {
        notifyObj.message = "Request unauthorized"
        setTimeout(function () {
            location.reload()
        }, 500)
    } else if (error.response.status === 422) {
        notifyObj.message = "Validation error occur"
        if (error.response.status === 422) {
            const formattedErrors = formatValidationErrors(error.response.data.errors);
            Object.assign(validationError, formattedErrors);
        }
    }

    notifyStore.pushNotification(notifyObj)
}

export const handleSuccess = (message) => {
    notifyStore.pushNotification({message: message, type: "success", time: 2000});
}
