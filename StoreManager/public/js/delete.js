function Check() {
    console.log("Check function called");
    let checked = confirm("本当に削除しますか？");
    console.log("User choice:", checked);
    return checked;
}
