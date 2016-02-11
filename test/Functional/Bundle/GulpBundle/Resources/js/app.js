import {Foo} from './foo';

class App {
    constructor(foo) {
        this.foo = foo;
    }

    fooBar() {
        console.log(this.foo.bar());
    }
}


const app = new App(new Foo());
app.fooBar();
