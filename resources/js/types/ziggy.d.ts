import { Config as ZiggyConfig, RouteName, RouteParams } from 'ziggy-js';

declare global {
    function route(): ZiggyConfig;
    function route<T extends RouteName>(
        name: T,
        params?: RouteParams<T>,
        absolute?: boolean,
    ): string;
}

declare module '@inertiajs/core' {
    interface PageProps {
        ziggy: ZiggyConfig & { location: string };
    }
}

export {};
