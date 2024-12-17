import { NgModule,  CUSTOM_ELEMENTS_SCHEMA, APP_INITIALIZER } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AuthModule } from './modules/auth/auth.module';
import { SharedModule } from './modules/shared/shared.module';
import { MaterialModule } from './modules/material/material.module';
import { AuthRoutingModule } from './modules/auth/auth-routing.module';
import { ExportAsModule } from 'ngx-export-as';
import { FilterPipe } from './pipes/filter.pipe';
import { PdfViewerModule } from 'ng2-pdf-viewer';
import { RouterModule } from '@angular/router';
import { FindIndexPipe } from './pipes/find-index.pipe';
import { AuthGuardService } from './services/guard/auth.guard.service';
import { AuthService } from './services/auth/auth.service';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { AuthInterceptor } from './services/guard/auth.intercept';
import { LimitCharactersPipe } from './pipes/limit-characters.pipe';
import { ErrorsService } from './services/errors.service';

export function initApp(authService: AuthService) {
  return () => authService.validateToken().toPromise();
}

@NgModule({
  declarations: [AppComponent],
  imports: [
    BrowserModule,
    PdfViewerModule,
    RouterModule.forRoot([]),
    AppRoutingModule,
    BrowserAnimationsModule,
    AuthRoutingModule,
    AuthModule,
    SharedModule,
    ExportAsModule,
    MaterialModule,
    HttpClientModule
  ],
  exports: [],
  providers: [
    ErrorsService,
    AuthGuardService,
   {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true,
    },
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}
