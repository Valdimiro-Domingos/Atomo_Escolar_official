import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'findIndex'
})
export class FindIndexPipe implements PipeTransform {

  transform(array: any[], item: any): number {
      return array.indexOf(item) + 1;
  }
}
