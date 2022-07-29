import Authenticated from "@/Layouts/Authenticated/Index";
import Flickity from "react-flickity-component";
import { Head } from "@inertiajs/inertia-react";
import FeaturedMovie from "@/Components/FeaturedMovie";
import MovieCard from "@/Components/MovieCard";

export default function Dashboard() {
    const flickityOptions = {
        "cellAlign": "left",
        "contain": true,
        "groupCells": 1,
        "wrapAround": false,
        "pageDots": false,
        "prevNextButtons": false,
        "draggable": ">1"
    }

    return <Authenticated>
        <Head>
            <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css" />
            <title>Dashboard</title>
        </Head>

        <div>
            <div className="font-semibold text-[22px] text-black mb-4">Featured Movies</div>
            <Flickity className="gap-[30px]" options={flickityOptions}>
                {[1, 2, 3].map(i => (
                    <FeaturedMovie key={i}
                        slug="the-batman-in-love"
                        name={`The batman in love ${i}`}
                        category="Action • Horror"
                        thumbnail={`/images/featured-${i}.png`}
                        rating={i + 1}
                    />
                ))}
            </Flickity>
        </div>
        <div className="mt-[50px]">
            <div className="font-semibold text-[22px] text-black mb-4">Browse</div>
            <Flickity className="gap-[30px]" options={flickityOptions}>
                {[1, 2, 3, 4, 5].map(i => (
                    <MovieCard key={i}
                        slug="the-batman-in-love"
                        name={`The batman in love ${i}`}
                        category="Action • Love"
                        thumbnail={`/images/browse-${i}.png`}
                    />
                ))}
            </Flickity>
        </div>
    </Authenticated>
};
