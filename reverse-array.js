const theArray = ['n','2','&','a','l','9','$','q','47','i','a','j','b','z','%','8'];
const reversedArray = [];;

theArray.forEach((element) => {
    if(/[a-zA-Z]|\d/g.test(element)) {
        reversedArray.unshift(element);
    }
});

theArray.forEach((element, index) => {
    if(!/[a-zA-Z]|\d/g.test(element)) {
        reversedArray.splice(index, 0, element);
    }
});

console.log(reversedArray);