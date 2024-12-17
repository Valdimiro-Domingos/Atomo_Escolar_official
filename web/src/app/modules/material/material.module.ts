import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatSidenavModule} from '@angular/material/sidenav'
import { MatExpansionModule } from '@angular/material/expansion'


@NgModule({
  declarations: [],
  imports: [
    CommonModule,
  ],
  exports: [
    MatSidenavModule,
    MatExpansionModule
  ]
})
export class MaterialModule { }
