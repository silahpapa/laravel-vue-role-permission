import axios, { AxiosInstance, AxiosResponse } from 'axios';

const Session = (): string => {
    const token = localStorage.getItem('access_token');
    return token ? token : '';
};

const api: AxiosInstance = axios.create({
    headers: {
        'Content-type': 'application/json'
    }
});

const addAuthorizationHeader = (headers: Record<string, string>) => {
    const accessToken = Session();
    if (accessToken) {
        headers.Authorization = `Bearer ${accessToken}`;
    }
    return headers;
};

export const doGet = async <T>(endPoint: string, data?: Record<string, unknown>): Promise<AxiosResponse<T>> => {
    const response = await api.get<T>(endPoint, {
        params: data,
        headers: addAuthorizationHeader({})
    });
    return response;
};

export const doPost = async <T>(endPoint: string, data: Record<string, unknown>): Promise<AxiosResponse<T>> => {
    const response = await api.post<T>(endPoint, data, {
        headers: addAuthorizationHeader({})
    });
    return response;
};

export const doPut = async <T>(endPoint: string, data: Record<string, unknown>): Promise<AxiosResponse<T>> => {
    const response = await api.put<T>(endPoint, data, {
        headers: addAuthorizationHeader({})
    });
    return response;
};

export const doPatch = async <T>(endPoint: string, data: Record<string, unknown>): Promise<AxiosResponse<T>> => {
    const response = await api.patch<T>(endPoint, data, {
        headers: addAuthorizationHeader({})
    });
    return response;
};

export const doDelete = async <T>(endPoint: string): Promise<AxiosResponse<T>> => {
    const response = await api.delete<T>(endPoint, {
        headers: addAuthorizationHeader({})
    });
    return response;
};
