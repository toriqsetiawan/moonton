export default function FlashMessage({ className, message = "" }) {
    return (
        <div
            className={`flex bg-green-100 rounded p-4 text-sm text-green-700 ${className}`}
        >
            {message}
        </div>
    );
}
