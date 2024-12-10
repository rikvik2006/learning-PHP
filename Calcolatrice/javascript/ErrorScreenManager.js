export class ErrorScreenManager {
    static errorScreen = document.querySelector(".error_container .error");

    static updateError(errorMessage) {
        this.errorScreen.innerText = errorMessage;
        this.errorScreen.style.display = "block";
    }

    static hideError() {
        this.errorScreen.style.display = "none";
    }
}
