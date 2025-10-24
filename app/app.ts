class User {
    public Name: string;
    public Surname: string;
    constructor(name: string, surname: string) {
        this.Name = name;
        this.Surname = surname;
    }
}
class Role {
    public Id: number;
    public Name: number;
}

class Main {
    newUser(): User {
        return new User('alex', 'ochnev');
    }
}