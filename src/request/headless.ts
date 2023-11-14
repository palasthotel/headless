
export const searchParamsAddHeadless = (searchParams: URLSearchParams, value: string = "true", name: string = "headless") => {
    searchParams.append(name, value);
}