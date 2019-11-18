function hasGetUserMedia() {
    return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}
if (!hasGetUserMedia()) {
    alert("navigator.mediaDevices.getUserMedia doesn\'t exist");
}