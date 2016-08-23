//input.validity = {  
//  valid: false // Поле валидно
//  customError: false // Установленно специальное сообщение ошибки
//  patternMismatch: false // Значение не удовлетворяет шаблону, установленному в атрибуте pattern
//  rangeOverflow: false // Значение превосходит атрибут max
//  rangeUnderflow: true // Значение меньше атрибута min
//  stepMismatch: true // Значение не соответствует указаному шагу
//  tooLong: false // Значение слишком длинное
//  tooShort: false // Значение слишком короткое
//  typeMismatch: false // Значение не соответствует указаному атрибуту type
//  valueMissing: false // Отсутствует обязательное значение
//};

/*
 * 	Validation.js
 * 			Version: 0.0.1
 * Project Page: http://
 *  		 Author: Fiodorov Nikita
 *			Website: http://nikitait.github.io/
 *				 Docs: http://nikitait.github.io/
 *    		 Repo: http://github.com/
 */
	
//formValidation('email','password');
function formValidation(){

/* ----------------------------
	CustomValidation прототип
- Отслеживает списка сообщений недействительности для этого входа
- Отслеживает , что срок действия необходимо выполнить для этого входа проверки
- Выполняет проверки корректности и посылает обратную связь к переднему концу
  1. addInvalidity -  Добавляет полученную ошибку в массив
  2. getInvalidities - Получаем общий текст сообщений об ошибках
  3. checkValidity - Метод, проверяющий валидность
  4. this.invalidities - массив сообщений об ошибках
  5. this.inputNode - ссылка на вход узла
  6. this.registerListener() - Метод, прикрепить слушателя
---------------------------- */

function CustomValidation(input) {
	this.invalidities = [];
	this.validityChecks = [];
	this.inputNode = input;
	console.log("input inputNode = " +this.inputNode);
	this.registerListener();
}

CustomValidation.prototype = {
	addInvalidity: function(message) {
		this.invalidities.push(message);
	},
	getInvalidities: function() {
		return this.invalidities.join('. \n');
	},
	checkValidity: function(input) {
		for ( var i = 0; i < this.validityChecks.length; i++ ) {
			var isInvalid = this.validityChecks[i].isInvalid(input);
			if (isInvalid) {
				this.addInvalidity(this.validityChecks[i].invalidityMessage);
			}
		  var requirementElement = this.validityChecks[i].element;
			if (requirementElement) {
				if (isInvalid) {
					requirementElement.classList.add('invalid');
					requirementElement.classList.remove('valid');
				} else {
					requirementElement.classList.remove('invalid');
					requirementElement.classList.add('valid');
				}
			} // end if requirementElement
		} // end for
	},
	checkInput: function() { // checkInput now encapsulated
		this.inputNode.CustomValidation.invalidities = [];
		this.checkValidity(this.inputNode);
		if ( this.inputNode.CustomValidation.invalidities.length === 0 && this.inputNode.value !== '' ) {
			this.inputNode.setCustomValidity('');
		} else {
			var message = this.inputNode.CustomValidation.getInvalidities();
			this.inputNode.setCustomValidity(message);
		}
	},
	registerListener: function() { //register the listener here
		var CustomValidation = this;
		console.log("registerListener inputNode = " +this.inputNode);
		this.inputNode.addEventListener('keyup', function() {
			CustomValidation.checkInput();
		});
	}
};



/* ----------------------------
	Validity Checks(Срок действия Проверки)
  Массивы проверки достоверности для каждого входа
  Состоящая из трех вещей
  1. isInvalid ( ) - функция , чтобы определить, является ли вход выполняет особое требование
  2. invalidityMessage - сообщение об ошибке для отображения , если поле является недействительным
  3. element - Элемент, который заявляет требование
  4. usernameValidityChecks
---------------------------- */

var usernameValidityChecks = [
	{
		isInvalid: function(input) {
			return input.value.length < 3| input.value.length > 50;
		},
		//This input needs to be at least 3 characters
		invalidityMessage: 'От 3 символов до 50',
		element: document.querySelector('label[for="username"] .input-requirements li:nth-child(1)')
	},
	{
		isInvalid: function(input) {
			var illegalCharacters = input.value.match(/[^a-zA-Z0-9]/g);
			return illegalCharacters ? true : false;
		},
		//Only letters and numbers are allowed
		invalidityMessage: 'Только цифры и буквы',
		element: document.querySelector('label[for="username"] .input-requirements li:nth-child(2)')
	}
];

var passwordValidityChecks = [
	{
		isInvalid: function(input) {
			return input.value.length < 6 | input.value.length > 100;
		},
		//This input needs to be between 8 and 100 characters
		invalidityMessage: 'От 6 до 100 символов',
		element: document.querySelector('label[for="password"] .input-requirements li:nth-child(1)')
	},
	/*{
		isInvalid: function(input) {
			return !input.value.match(/[0-9]/g);
		},
		//At least 1 number is required
		invalidityMessage: 'Минимум 1 цифра',
		element: document.querySelector('label[for="password"] .input-requirements li:nth-child(2)')
	},*/
	{
		isInvalid: function(input) {
			return !input.value.match(/[A-Za-z]/g);
		},
		//At least 1 lowercase letter is required
		invalidityMessage: 'Минимум 1  буква',
		element: document.querySelector('label[for="password"] .input-requirements li:nth-child(2)')
	}/*,
	{
		isInvalid: function(input) {
			return !input.value.match(/[A-Z]/g);
		},
		//At least 1 uppercase letter is required
		invalidityMessage: 'Минимум 1 заглавная буква',
		element: document.querySelector('label[for="password"] .input-requirements li:nth-child(4)')
	},*/
	/*{
		isInvalid: function(input) {
			return !input.value.match(/[\!\@\#\$\%\^\&\*]/g);
		},
		//You need one of the required special characters
		invalidityMessage: 'Минимум 1 спец символ @',
		element: document.querySelector('label[for="password"] .input-requirements li:nth-child(5)')
	}*/
];

var passwordRepeatValidityChecks = [
	{
		isInvalid: function() {
			return passwordRepeatInput.value != passwordInput.value;
		},
		//This password needs to match the first one
		invalidityMessage: 'Пароли должны совпадать'
	}
];

var emailValidityChecks = [
	{
		isInvalid: function(input) {
			return !input.value.match(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		},
		//Неверно
		invalidityMessage: 'Неверно',
		element: document.querySelector('label[for="email"] .input-requirements li:nth-child(1)')
	}
];
/* ----------------------------
	Setup CustomValidation
  Определение переменных прототипa
---------------------------- */
var args = Array.prototype.slice.call(arguments, 1);
	console.log(args);
if(args.indexOf('email')>-1){
	console.log('email');
	var emailInput = document.getElementById('email');
	emailInput.CustomValidation = new CustomValidation(emailInput);
	emailInput.CustomValidation.validityChecks = emailValidityChecks;
}
if(args.indexOf('link')>-1){
	var linkInput = document.getElementById('link');
	linkInput.CustomValidation = new CustomValidation(linkInput);
	linkInput.CustomValidation.validityChecks = linkValidityChecks;
}
if(args.indexOf('title')>-1){
	var titleInput = document.getElementById('title');
	titleInput.CustomValidation = new CustomValidation(titleInput);
	titleInput.CustomValidation.validityChecks = titleValidityChecks;
}
if(args.indexOf('username')>-1){
	var usernameInput = document.getElementById('username');
	usernameInput.CustomValidation = new CustomValidation(usernameInput);
	usernameInput.CustomValidation.validityChecks = 	usernameValidityChecks;
}
if(args.indexOf('password')>-1){
	console.log('password');
	var passwordInput = document.getElementById('password');
	passwordInput.CustomValidation = new CustomValidation(passwordInput);
	passwordInput.CustomValidation.validityChecks = passwordValidityChecks;
}
if(args.indexOf('password_repeat')>-1){
	var passwordRepeatInput = document.getElementById('password_repeat');
	passwordRepeatInput.CustomValidation = new CustomValidation(passwordRepeatInput);
	passwordRepeatInput.CustomValidation.validityChecks = passwordRepeatValidityChecks;
}


/* ----------------------------
	Event Listeners
---------------------------- */

var inputs = document.querySelectorAll('input:not([type="submit"])');
var submit = document.querySelector('input[type="submit"');
var form = document.getElementById('form-valid');
	
function validate() {
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].CustomValidation.checkInput();
		console.log("inputs = " + inputs[i]);
	}
}

submit.addEventListener('click', validate);
form.addEventListener('submit', validate);


}