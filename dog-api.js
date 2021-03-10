const dogApi = 'https://dog.ceo/api';
const houndBreedsEndpoint = '/breed/hound/list';
const houndImagesEndpoint = '/breed/hound/images';

fetch(dogApi + houndBreedsEndpoint)
    .then(res => res.json())
    .then(data => console.table(data.message));

fetch(dogApi + houndImagesEndpoint)
    .then(res => res.json())
    .then(data => console.table(data.message));