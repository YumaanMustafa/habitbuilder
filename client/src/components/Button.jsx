import clsx from 'clsx';
import { twMerge } from 'tailwind-merge';

export const Button = ({ children, variant = 'primary', className, ...props }) => {
    const variants = {
        primary: "bg-primary hover:bg-indigo-600 text-white shadow-lg shadow-indigo-500/30",
        secondary: "bg-surface hover:bg-gray-700 text-text border border-gray-600",
        danger: "bg-red-500 hover:bg-red-600 text-white",
    };

    return (
        <button
            className={twMerge(
                "px-6 py-2 rounded-lg font-medium transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed",
                variants[variant],
                className
            )}
            {...props}
        >
            {children}
        </button>
    );
};
