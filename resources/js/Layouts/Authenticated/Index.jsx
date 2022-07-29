import Sidebar from "./Sidebar";
import Topbar from "./Topbar";

export default function Authenticated({ children }) {
    return (
        <>
            <div className="mx-auto max-w-screen hidden lg:block">
                {/* start: sidebar */}
                <Sidebar />
                {/* end: sidebar */}

                {/* start: content */}
                <div className="ml-[300px] px-[50px]">
                    <div className="py-10 flex flex-col gap-[50px]">
                        {/* start: topbar */}
                        <Topbar />
                        {/* end: topbar */}
                        <main>{children}</main>
                    </div>
                </div>
                {/* end: content */}
            </div>
            <div className="mx-auto px-4 w-full h-screen lg:hidden flex bg-black">
                <div className="text-white text-2xl text-center leading-snug font-medium my-auto">
                    Sorry, this page only supported on 1024px screen or above
                </div>
            </div>
        </>
    )
};
