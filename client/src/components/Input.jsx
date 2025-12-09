import clsx from 'clsx';
import { twMerge } from 'tailwind-merge';

export const Input = ({ label, className, ...props }) => {
    return (
        <div className="mb-4">
            {label && <label className="block text-sm font-medium text-muted mb-1">{label}</label>}
            <input
                className={twMerge(
                    "w-full px-4 py-2 bg-surface text-text border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition-colors",
                    className
                )}
                {...props}
            />
        </div>
    );
};
