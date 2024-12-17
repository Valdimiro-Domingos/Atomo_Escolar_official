import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environmentSettings } from 'src/environments/environment.dev';
import { BehaviorSubject } from 'rxjs';
import { AuthService } from 'src/app/services/auth/auth.service';
import { AlertService } from './alert/alert.service';


@Injectable({
    providedIn: 'root',
})
export class ErrorsService {
    // Define o BehaviorSubject com um valor inicial vazio
    isError = new BehaviorSubject<string>('');

    // Injeta o serviço de alerta
    constructor(private alert: AlertService) { }

    // Método para definir o erro
    setError(err: any): void {
        const errorKeywords = ['The', 'Class', 'failure', 'Call', 'Order', 'include'];
        const errorMessage = errorKeywords.find(keyword => err.toString().includes(keyword));
        if (errorMessage) {
            this.alert.AlertError('Pedimos que tente novamente. Caso o problema persista, contate nosso pessoal!');
        } else {
            // Caso nenhuma substring seja encontrada, define uma mensagem padrão
            this.alert.AlertError(err);
        }
    }
}