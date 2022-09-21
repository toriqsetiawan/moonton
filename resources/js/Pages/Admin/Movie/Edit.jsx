import Button from "@/Components/Button";
import Checkbox from "@/Components/Checkbox";
import Input from "@/Components/Input";
import Label from "@/Components/Label";
import ValidationErrors from "@/Components/ValidationErrors";
import Authenticated from "@/Layouts/Authenticated/Index";
import { Inertia } from "@inertiajs/inertia";
import { Head, useForm } from "@inertiajs/inertia-react";

export default function Create({ auth, movie }) {
    const { data, setData, processing, errors } = useForm({
        ...movie,
    });

    const onHandleChange = (event) => {
        setData(
            event.target.name,
            event.target.type === "file"
                ? event.target.files[0]
                : event.target.value
        );
    };

    const submit = (e) => {
        e.preventDefault();

        if (data.thumbnail === movie.thumbnail) delete data.thumbnail;

        Inertia.post(route("admin.dashboard.movie.update", movie.id), {
            _method: "PUT",
            ...data,
        });
    };

    return (
        <Authenticated auth={auth}>
            <Head title="Admin - Update Movie" />
            <h1 className="text-xl">Update Movie: {movie.name}</h1>
            <hr className="mb-4" />
            <ValidationErrors errors={errors} />
            <form onSubmit={submit}>
                <div>
                    <Label forInput="name" value="Name" />
                    <Input
                        type="text"
                        name="name"
                        defaultValue={movie.name}
                        variant="primary-outline"
                        handleChange={onHandleChange}
                        placeholder="Enter the name of movie"
                        isError={errors.name}
                    />
                </div>
                <div className="mt-4">
                    <Label forInput="category" value="Category" />
                    <Input
                        type="text"
                        name="category"
                        defaultValue={movie.category}
                        variant="primary-outline"
                        handleChange={onHandleChange}
                        placeholder="Enter category"
                        isError={errors.category}
                    />
                </div>
                <div className="mt-4">
                    <Label forInput="video_url" value="Video URL" />
                    <Input
                        type="url"
                        name="video_url"
                        defaultValue={movie.video_url}
                        variant="primary-outline"
                        handleChange={onHandleChange}
                        placeholder="Enter video url"
                        isError={errors.video_url}
                    />
                </div>
                <div className="mt-4">
                    <Label forInput="thumbnail" value="Thumbnail" />
                    <img src={`/storage/${movie.thumbnail}`} className="w-32" />
                    <Input
                        type="file"
                        name="thumbnail"
                        variant="primary-outline"
                        handleChange={onHandleChange}
                        placeholder="Insert thumbnail"
                        isError={errors.thumbnail}
                    />
                </div>
                <div className="mt-4">
                    <Label forInput="rating" value="Rating" />
                    <Input
                        type="number"
                        name="rating"
                        defaultValue={movie.rating}
                        variant="primary-outline"
                        handleChange={onHandleChange}
                        placeholder="Enter rating"
                        isError={errors.rating}
                    />
                </div>
                <div className="mt-4 flex flex-row items-center">
                    <Label
                        className="mt-2 mr-2"
                        forInput="is_featured"
                        value="Is featured"
                    />
                    <Checkbox
                        name="is_featured"
                        handleChange={(e) =>
                            setData("is_featured", e.target.checked)
                        }
                        checked={movie.is_featured}
                    />
                </div>
                <Button type="submit" className="mt-4" processing={processing}>
                    Save
                </Button>
            </form>
        </Authenticated>
    );
}
