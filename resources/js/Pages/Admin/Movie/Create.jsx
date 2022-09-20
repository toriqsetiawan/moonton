import Button from "@/Components/Button";
import Checkbox from "@/Components/Checkbox";
import Input from "@/Components/Input";
import Label from "@/Components/Label";
import ValidationErrors from "@/Components/ValidationErrors";
import Authenticated from "@/Layouts/Authenticated/Index";
import { Head, useForm } from "@inertiajs/inertia-react";

export default function Create({ auth }) {
    const { setData, post, processing, errors } = useForm({
        name: "",
        category: "",
        video_url: "",
        thumbnail: "",
        rating: "",
        is_featured: false,
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

        post(route("admin.dashboard.movie.store"));
    };

    return (
        <Authenticated auth={auth}>
            <Head title="Admin - Create Movie" />
            <h1 className="text-xl">Insert a new Movie</h1>
            <hr className="mb-4" />
            <ValidationErrors errors={errors} />
            <form onSubmit={submit}>
                <div>
                    <Label forInput="name" value="Name" />
                    <Input
                        type="text"
                        name="name"
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
                        variant="primary-outline"
                        handleChange={onHandleChange}
                        placeholder="Enter video url"
                        isError={errors.video_url}
                    />
                </div>
                <div className="mt-4">
                    <Label forInput="thumbnail" value="Thumbnail" />
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
                        variant="primary-outline"
                        handleChange={onHandleChange}
                        placeholder="Enter rating"
                        isError={errors.rating}
                    />
                </div>
                <div className="mt-4 flex flex-row items-center">
                    <Label className="mt-2 mr-2" forInput="is_featured" value="Is featured" />
                    <Checkbox
                        name="is_featured"
                        handleChange={(e) =>
                            setData("is_featured", e.target.checked)
                        }
                    />
                </div>
                <Button
                    type="submit"
                    className="mt-4"
                    processing={processing}
                >
                    Save
                </Button>
            </form>
        </Authenticated>
    );
}
