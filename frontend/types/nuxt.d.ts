import type { AxiosInstance } from "axios";

declare module "#app" {
    interface NuxtApp {
        $api: AxiosInstance; 
    }
}
declare module "#vue" {
    interface ComponentCustomProporties {
        $api: AxiosInstance;
    }
}

export {};