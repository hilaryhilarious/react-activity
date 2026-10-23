import {useState} from 'react';


export default function Form()
{
    const [name, setName] = useState("");

    //user defined event
    function handleSubmit(e) {
        //to avoid multiple submission
        e.preventDefault();
        console.log(name);
    }

    return (
        //to trigger when the form is submitted
        <form method="POST" onSubmit={handleSubmit}> 
            <input
                value = {name}
                onChange = {(e) => setName(e.target.value)} 
                placeholder="Input your name"
            />
            <button>Submit</button>
        </form>
    );
}

//useState like a variable - multiple should use curly braces. 
