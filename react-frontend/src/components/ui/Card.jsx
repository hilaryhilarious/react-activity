import '../../App.css'

//functional component - use to organize the code and reuse it in other components
export default function Card({name, description}) {
	return (
        <div className="card">
            <h1>{name}</h1>
            <p>{description}</p>
        </div>
    );
}

